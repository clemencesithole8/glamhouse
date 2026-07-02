<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Service;
use App\Models\TimeSlot;
use App\Notifications\BookingSubmittedClientNotification;
use App\Notifications\BookingSubmittedAdminNotification;
use App\Services\BookingAvailabilityService;
use App\Services\ImageOptimizer;
use App\Services\WhatsAppNotifier;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Throwable;

class BookingController extends Controller
{
    public function create(Request $request)
    {
        return view('booking.create', [
            'services' => Schema::hasTable('services')
                ? Service::where('is_active', true)->get()
                : collect(),
            'timeSlots' => Schema::hasTable('time_slots')
                ? TimeSlot::where('is_active', true)->orderBy('start_time')->get()
                : collect(),
            'prefill' => $this->prefillFromBooking($request),
        ]);
    }

    public function store(StoreBookingRequest $request, BookingAvailabilityService $availability, ImageOptimizer $images)
    {
        $data = $request->validated();

        if (! Schema::hasTable('bookings') || ! Schema::hasTable('services')) {
            return back()
                ->withInput()
                ->withErrors(['service_id' => 'Online booking is still being prepared. Please try again shortly.']);
        }

        if (! $availability->isDateBookable($data['appointment_date'])) {
            return back()
                ->withInput()
                ->withErrors(['appointment_date' => 'That date is unavailable. Please choose another date.']);
        }

        try {
            $booking = DB::transaction(function () use ($request, $data, $availability, $images): Booking {
                if (! empty($data['time_slot_id']) && ! $availability->isSlotAvailable(
                    $data['appointment_date'],
                    (int) $data['time_slot_id'],
                    (bool) $data['is_outcall'],
                    null,
                    true,
                )) {
                    throw ValidationException::withMessages([
                        'time_slot_id' => 'That time slot is no longer available. Please select another.',
                    ]);
                }

                if ($request->hasFile('reference_look')) {
                    $stored = $images->store($request->file('reference_look'), 'reference-looks', thumbnailWidth: 360);
                    $images->deleteStoredImages([$stored['path'], $stored['thumbnail_path']]);
                    $data['reference_image_path'] = $stored['webp_path'];
                }

                return Booking::create([
                    ...$data,
                    'status' => 'pending',
                ]);
            });
        } catch (QueryException $exception) {
            if (! $this->isSlotConflictException($exception)) {
                throw $exception;
            }

            return back()
                ->withInput()
                ->withErrors(['time_slot_id' => 'That time slot was just booked. Please select another.']);
        }

        $this->sendBookingNotifications(
            $booking->loadMissing(['service', 'timeSlot'])
        );

        return redirect()
            ->to(URL::temporarySignedRoute('booking.pdf', now()->addHours(24), ['bookingId' => $booking->id]))
            ->with('success', 'Booking submitted! You can download your booking summary PDF below.');
    }

    private function prefillFromBooking(Request $request): array
    {
        if (! $request->filled('copy_from') || ! $request->user() || ! Schema::hasTable('bookings')) {
            return [];
        }

        $booking = Booking::query()
            ->whereKey($request->integer('copy_from'))
            ->where('email', $request->user()->email)
            ->first();

        if (! $booking) {
            return [];
        }

        return [
            'full_name' => $booking->full_name,
            'phone' => $booking->phone,
            'email' => $booking->email,
            'location_area' => $booking->location_area,
            'service_id' => $booking->service_id,
            'event_type' => $booking->event_type,
            'is_outcall' => $booking->is_outcall ? '1' : '0',
            'outcall_address' => $booking->outcall_address,
            'skin_type' => $booking->skin_type,
            'allergies_notes' => $booking->allergies_notes,
            'has_done_pro_makeup' => $booking->has_done_pro_makeup ? '1' : '0',
        ];
    }

    private function isSlotConflictException(QueryException $exception): bool
    {
        $message = strtolower($exception->getMessage());

        return $exception->getCode() === '23000'
            || str_contains($message, 'bookings_appointment_date_time_slot_id_unique')
            || str_contains($message, 'unique constraint failed: bookings.appointment_date, bookings.time_slot_id');
    }

    private function sendBookingNotifications(Booking $booking): void
    {
        $issues = [];

        if (! empty($booking->email)) {
            try {
                $booking->notify(new BookingSubmittedClientNotification($booking));
            } catch (Throwable $e) {
                Log::error('Client booking confirmation failed to dispatch.', [
                    'booking_id' => $booking->id,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        $adminEmailQueued = false;
        $adminEmail = trim((string) config('glamhouse.admin_email'));

        if ($adminEmail !== '') {
            try {
                Notification::route('mail', $adminEmail)
                    ->notify(new BookingSubmittedAdminNotification($booking));

                $adminEmailQueued = true;
            } catch (Throwable $e) {
                Log::error('Admin booking email alert failed to dispatch.', [
                    'booking_id' => $booking->id,
                    'admin_email' => $adminEmail,
                    'message' => $e->getMessage(),
                ]);

                $issues[] = 'Admin email alert failed to dispatch.';
            }
        } else {
            Log::warning('Admin booking email alert skipped: no admin email configured.', [
                'booking_id' => $booking->id,
            ]);

            $issues[] = 'Admin email alert is not configured.';
        }

        $whatsappSent = false;
        $whatsappEnabled = (bool) config('glamhouse.whatsapp_notifications.enabled');

        try {
            $whatsappSent = app(WhatsAppNotifier::class)->sendNewBookingAlert($booking);
        } catch (Throwable $e) {
            Log::error('WhatsApp booking alert failed to dispatch.', [
                'booking_id' => $booking->id,
                'message' => $e->getMessage(),
            ]);
        }

        if (! $whatsappSent && ($whatsappEnabled || ! $adminEmailQueued)) {
            $issues[] = 'WhatsApp admin alert was not delivered; verify it is intentionally disabled or configure GLAMHOUSE_WHATSAPP_* settings.';
        }

        if (! $adminEmailQueued && ! $whatsappSent) {
            array_unshift($issues, 'No automated admin alert was delivered; check this booking manually.');
        }

        $this->recordNotificationIssues($booking, $issues);
    }

    private function recordNotificationIssues(Booking $booking, array $issues): void
    {
        $issues = array_values(array_unique(array_filter($issues)));

        if ($issues === []) {
            return;
        }

        $alert = '[Notification alert] '
            . now()->format('Y-m-d H:i')
            . ': '
            . implode(' ', $issues);

        $existingNotes = trim((string) $booking->admin_notes);

        $booking->forceFill([
            'admin_notes' => $existingNotes === ''
                ? $alert
                : $existingNotes."\n".$alert,
        ])->save();
    }
}
