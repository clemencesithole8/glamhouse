<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Service;
use App\Models\TimeSlot;
use App\Notifications\BookingSubmittedClientNotification;
use App\Notifications\BookingSubmittedAdminNotification;
use Illuminate\Support\Facades\Notification;

class BookingController extends Controller
{
    public function create()
    {
        return view('booking.create', [
            'services' => Service::where('is_active', true)->get(),
            'timeSlots' => TimeSlot::where('is_active', true)->orderBy('start_time')->get(),
        ]);
    }

    public function store(StoreBookingRequest $request)
    {
        $data = $request->validated();

        // Store upload if present
        if ($request->hasFile('reference_look')) {
            $data['reference_image_path'] = $request->file('reference_look')->store('reference-looks', 'public');
        }

        // Conflict prevention: if time_slot_id selected, ensure no pending/confirmed booking exists
        if (!empty($data['time_slot_id'])) {
            $conflict = Booking::query()
                ->whereDate('appointment_date', $data['appointment_date'])
                ->where('time_slot_id', $data['time_slot_id'])
                ->whereIn('status', ['pending','confirmed'])
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

        // WhatsApp placeholder: implement with Cloud API/Twilio via queued Job later
        // dispatch(new SendWhatsAppBookingReceivedJob($booking));

        return redirect()
            ->route('booking.pdf', $booking)
            ->with('success', 'Booking submitted! You can download your booking summary PDF below.');
    }
}
