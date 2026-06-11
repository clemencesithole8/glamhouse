<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        if (! Schema::hasTable('bookings')) {
            return view('admin.bookings.index', [
                'bookings' => $this->emptyPaginator($request, 20),
            ]);
        }

        [$relations, $hasServices, $hasTimeSlots, $hasPayments] = $this->availableRelations(['service', 'timeSlot']);

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
        ]);
    }

    public function updateStatus(Request $request, string $bookingId)
    {
        $booking = $this->findBookingOrFail($bookingId);

        $data = $request->validate([
            'status' => ['required','in:pending,confirmed,completed,cancelled']
        ]);

        $booking->update(['status' => $data['status']]);

        // If cancelled, free slot (so unique index doesn't lock future bookings)
        if ($data['status'] === 'cancelled') {
            $booking->update(['time_slot_id' => null]);
        }

        return back()->with('success', 'Status updated.');
    }

    public function cancel(string $bookingId)
    {
        $booking = $this->findBookingOrFail($bookingId);

        $booking->update([
            'status' => 'cancelled',
            'time_slot_id' => null,
        ]);

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
}
