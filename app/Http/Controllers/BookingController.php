<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Service;
use App\Models\TimeSlot;
use App\Notifications\BookingSubmittedClientNotification;
use App\Notifications\BookingSubmittedAdminNotification;
use App\Services\WhatsAppNotifier;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

class BookingController extends Controller
{
    public function create()
    {
        return view('booking.create', [
            'services' => Schema::hasTable('services')
                ? Service::where('is_active', true)->get()
                : collect(),
            'timeSlots' => Schema::hasTable('time_slots')
                ? TimeSlot::where('is_active', true)->orderBy('start_time')->get()
                : collect(),
        ]);
    }

    public function store(StoreBookingRequest $request)
    {
        $data = $request->validated();

        if (! Schema::hasTable('bookings') || ! Schema::hasTable('services')) {
            return back()
                ->withInput()
                ->withErrors(['service_id' => 'Online booking is still being prepared. Please try again shortly.']);
        }

        // Store upload if present
        if ($request->hasFile('reference_look')) {
            $data['reference_image_path'] = $request->file('reference_look')->store('reference-looks', 'public');
        }

        // Conflict prevention: if time_slot_id selected, ensure no pending/confirmed booking exists
        if (!empty($data['time_slot_id']) && Schema::hasTable('time_slots')) {
            $conflict = Booking::query()
                ->whereDate('appointment_date', $data['appointment_date'])
                ->where('time_slot_id', $data['time_slot_id'])
                ->whereIn('status', ['pending', 'confirmed'])
                ->exists();

            if ($conflict) {
                return back()
                    ->withInput()
                    ->withErrors(['time_slot_id' => 'That time slot is no longer available. Please select another.']);
            }
        }

        $booking = Booking::create([
            ...$data,
            'status' => 'pending',
        ]);

        // Email notifications
        if (!empty($booking->email)) {
            $booking->notify(new BookingSubmittedClientNotification($booking));
        }

        $adminEmail = config('glamhouse.admin_email');
        if ($adminEmail) {
            Notification::route('mail', $adminEmail)
                ->notify(new BookingSubmittedAdminNotification($booking));
        }

        // Optional WhatsApp admin alert (enabled through env/config)
        app(WhatsAppNotifier::class)->sendNewBookingAlert(
            $booking->loadMissing(['service', 'timeSlot'])
        );

        return redirect()
            ->to(URL::temporarySignedRoute('booking.pdf', now()->addHours(24), ['bookingId' => $booking->id]))
            ->with('success', 'Booking submitted! You can download your booking summary PDF below.');
    }
}
