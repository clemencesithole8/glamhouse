<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\TimeSlot;
use App\Notifications\BookingRescheduledClientNotification;
use App\Notifications\BookingStatusUpdatedClientNotification;
use App\Services\BookingAvailabilityService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Throwable;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        if (! Schema::hasTable('bookings')) {
            return view('admin.bookings.index', [
                'bookings' => $this->emptyPaginator($request, 20),
            ]);
        }

        [$relations, $hasServices, $hasTimeSlots, $hasPayments] = $this->availableRelations(['service', 'timeSlot', 'payments']);

        $q = Booking::query()->latest();

        if ($relations !== []) {
            $q->with($relations);
        }

        if ($request->filled('status')) $q->where('status', $request->string('status'));
        if ($request->filled('date')) $q->whereDate('appointment_date', $request->date('date'));
        if ($request->filled('search')) {
            $s = $request->string('search');
            $q->where(function($w) use ($s) {
                $w->where('full_name','like',"%{$s}%")
                  ->orWhere('phone','like',"%{$s}%")
                  ->orWhere('email','like',"%{$s}%");
            });
        }

        $bookings = $q->paginate(20)->withQueryString();

        $bookings->getCollection()->each(fn (Booking $booking) => $this->setMissingRelationDefaults(
            $booking,
            $hasServices,
            $hasTimeSlots,
            $hasPayments,
        ));

        return view('admin.bookings.index', [
            'bookings' => $bookings,
        ]);
    }

    public function show(string $bookingId)
    {
        $booking = $this->findBookingOrFail($bookingId, ['service', 'timeSlot', 'payments']);

        return view('admin.bookings.show', [
            'booking' => $booking,
            'canRecordPayments' => Schema::hasTable('payments'),
            'timeSlots' => Schema::hasTable('time_slots')
                ? TimeSlot::query()->where('is_active', true)->orderBy('start_time')->get()
                : collect(),
        ]);
    }

    public function updateStatus(Request $request, string $bookingId)
    {
        $booking = $this->findBookingOrFail($bookingId);

        $data = $request->validate([
            'status' => ['required','in:'.implode(',', Booking::STATUSES)]
        ]);

        $previousStatus = $booking->status;
        $before = $booking->getOriginal();

        $payload = ['status' => $data['status']];

        // If cancelled, free slot (so unique index doesn't lock future bookings)
        if ($data['status'] === 'cancelled') {
            $payload['time_slot_id'] = null;
        }

        $booking->update($payload);

        if ($previousStatus !== $booking->status && in_array($booking->status, ['confirmed', 'completed', 'cancelled'], true)) {
            $this->sendStatusNotification($booking->fresh(), $previousStatus);
        }

        AuditLog::record('booking.status_updated', $booking, $before, $booking->fresh()->toArray(), 'Booking status updated.');

        return back()->with('success', 'Status updated.');
    }

    public function updateFinancials(Request $request, string $bookingId)
    {
        $booking = $this->findBookingOrFail($bookingId);

        $data = $request->validate([
            'deposit_amount' => ['nullable','integer','min:0'],
            'total_amount' => ['nullable','integer','min:0'],
            'admin_notes' => ['nullable','string','max:10000'],
        ]);

        if (
            $data['deposit_amount'] !== null
            && $data['total_amount'] !== null
            && (int) $data['deposit_amount'] > (int) $data['total_amount']
        ) {
            return back()
                ->withInput()
                ->withErrors(['deposit_amount' => 'The deposit cannot be greater than the total quote.']);
        }

        $before = $booking->getOriginal();
        $booking->update($data);

        AuditLog::record('booking.financials_updated', $booking, $before, $booking->fresh()->toArray(), 'Booking financial details updated.');

        return back()->with('success', 'Financial details updated.');
    }

    public function reschedule(Request $request, string $bookingId, BookingAvailabilityService $availability)
    {
        $booking = $this->findBookingOrFail($bookingId, ['timeSlot']);
        $hasTimeSlots = Schema::hasTable('time_slots');

        $timeSlotRules = ['nullable','integer'];

        if ($hasTimeSlots) {
            $timeSlotRules[] = 'exists:time_slots,id';
        }

        $data = $request->validate([
            'appointment_date' => ['required','date','after_or_equal:today'],
            'time_slot_id' => $timeSlotRules,
            'preferred_time_text' => ['nullable','string','max:100'],
        ]);

        if (empty($data['time_slot_id']) && empty($data['preferred_time_text'])) {
            return back()
                ->withInput()
                ->withErrors(['time_slot_id' => 'Choose a time slot or enter a preferred time.']);
        }

        if (! $availability->isDateBookable($data['appointment_date'])) {
            return back()
                ->withInput()
                ->withErrors(['appointment_date' => 'That date is unavailable. Please choose another date.']);
        }

        $before = $booking->getOriginal();
        $previousDate = $booking->appointment_date?->format('D, d M Y') ?? 'Date pending';
        $previousTime = $this->bookingTimeLabel($booking);

        try {
            $updatedBooking = DB::transaction(function () use ($booking, $data, $availability): Booking {
                $lockedBooking = Booking::query()->lockForUpdate()->findOrFail($booking->id);

                if (! empty($data['time_slot_id']) && ! $availability->isSlotAvailable(
                    $data['appointment_date'],
                    (int) $data['time_slot_id'],
                    (bool) $lockedBooking->is_outcall,
                    $lockedBooking,
                    true,
                )) {
                    throw ValidationException::withMessages([
                        'time_slot_id' => 'That time slot is no longer available. Please select another.',
                    ]);
                }

                $lockedBooking->update([
                    'appointment_date' => $data['appointment_date'],
                    'time_slot_id' => $data['time_slot_id'] ?? null,
                    'preferred_time_text' => $data['preferred_time_text'] ?? null,
                    'status' => $lockedBooking->status === 'cancelled' ? 'pending' : $lockedBooking->status,
                    'reschedule_requested_at' => null,
                    'reschedule_note' => null,
                ]);

                return $lockedBooking->fresh(Schema::hasTable('time_slots') ? ['timeSlot'] : []);
            });
        } catch (QueryException $exception) {
            if (! $this->isSlotConflictException($exception)) {
                throw $exception;
            }

            return back()
                ->withInput()
                ->withErrors(['time_slot_id' => 'That time slot was just booked. Please select another.']);
        }

        $this->sendRescheduleNotification($updatedBooking, $previousDate, $previousTime);
        AuditLog::record('booking.rescheduled', $updatedBooking, $before, $updatedBooking->fresh()->toArray(), 'Booking rescheduled.');

        return back()->with('success', 'Booking rescheduled.');
    }

    public function cancel(string $bookingId)
    {
        $booking = $this->findBookingOrFail($bookingId);
        $previousStatus = $booking->status;
        $before = $booking->getOriginal();

        $booking->update([
            'status' => 'cancelled',
            'time_slot_id' => null,
        ]);

        if ($previousStatus !== 'cancelled') {
            $this->sendStatusNotification($booking->fresh(), $previousStatus);
        }

        AuditLog::record('booking.cancelled', $booking, $before, $booking->fresh()->toArray(), 'Booking cancelled and slot freed.');

        return back()->with('success', 'Booking cancelled and slot freed.');
    }

    private function findBookingOrFail(string $bookingId, array $requestedRelations = []): Booking
    {
        abort_unless(Schema::hasTable('bookings'), 404);

        [$relations, $hasServices, $hasTimeSlots, $hasPayments] = $this->availableRelations($requestedRelations);

        $query = Booking::query();

        if ($relations !== []) {
            $query->with($relations);
        }

        $booking = $query->findOrFail($bookingId);

        $this->setMissingRelationDefaults($booking, $hasServices, $hasTimeSlots, $hasPayments);

        return $booking;
    }

    private function availableRelations(array $requestedRelations): array
    {
        $hasServices = Schema::hasTable('services');
        $hasTimeSlots = Schema::hasTable('time_slots');
        $hasPayments = Schema::hasTable('payments');
        $relations = [];

        if (in_array('service', $requestedRelations, true) && $hasServices) {
            $relations[] = 'service';
        }

        if (in_array('timeSlot', $requestedRelations, true) && $hasTimeSlots) {
            $relations[] = 'timeSlot';
        }

        if (in_array('payments', $requestedRelations, true) && $hasPayments) {
            $relations[] = 'payments';
        }

        return [$relations, $hasServices, $hasTimeSlots, $hasPayments];
    }

    private function setMissingRelationDefaults(
        Booking $booking,
        bool $hasServices,
        bool $hasTimeSlots,
        bool $hasPayments,
    ): void {
        if (! $hasServices) {
            $booking->setRelation('service', null);
        }

        if (! $hasTimeSlots) {
            $booking->setRelation('timeSlot', null);
        }

        if (! $hasPayments) {
            $booking->setRelation('payments', collect());
        }
    }

    private function emptyPaginator(Request $request, int $perPage): LengthAwarePaginator
    {
        $paginator = new LengthAwarePaginator([], 0, $perPage, $request->integer('page', 1), [
            'path' => $request->url(),
        ]);

        return $paginator->appends($request->query());
    }

    private function bookingTimeLabel(Booking $booking): string
    {
        return $booking->timeSlot
            ? $booking->timeSlot->start_time.' - '.$booking->timeSlot->end_time
            : ($booking->preferred_time_text ?: 'Time pending');
    }

    private function sendStatusNotification(?Booking $booking, string $previousStatus): void
    {
        if (! $booking || empty($booking->email)) {
            return;
        }

        try {
            $booking->notify(new BookingStatusUpdatedClientNotification($booking, $previousStatus));
        } catch (Throwable $exception) {
            Log::error('Booking status notification failed to dispatch.', [
                'booking_id' => $booking->id,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    private function sendRescheduleNotification(Booking $booking, string $previousDate, string $previousTime): void
    {
        if (empty($booking->email)) {
            return;
        }

        try {
            $booking->notify(new BookingRescheduledClientNotification($booking, $previousDate, $previousTime));
        } catch (Throwable $exception) {
            Log::error('Booking reschedule notification failed to dispatch.', [
                'booking_id' => $booking->id,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    private function isSlotConflictException(QueryException $exception): bool
    {
        $message = strtolower($exception->getMessage());

        return $exception->getCode() === '23000'
            || str_contains($message, 'bookings_appointment_date_time_slot_id_unique')
            || str_contains($message, 'unique constraint failed: bookings.appointment_date, bookings.time_slot_id');
    }
}
