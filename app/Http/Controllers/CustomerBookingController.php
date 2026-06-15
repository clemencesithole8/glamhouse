<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Notifications\BookingRescheduleRequestedAdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;

class CustomerBookingController extends Controller
{
    public function requestReschedule(Request $request, string $bookingId)
    {
        abort_unless(Schema::hasTable('bookings'), 404);

        $booking = Booking::query()
            ->whereKey($bookingId)
            ->where('email', $request->user()->email)
            ->firstOrFail();

        $data = $request->validate([
            'reschedule_note' => ['nullable','string','max:1000'],
        ]);

        $booking->update([
            'reschedule_requested_at' => now(),
            'reschedule_note' => $data['reschedule_note'] ?? null,
        ]);

        $adminEmail = config('glamhouse.admin_email');

        if ($adminEmail) {
            $relations = [];

            if (Schema::hasTable('services')) {
                $relations[] = 'service';
            }

            if (Schema::hasTable('time_slots')) {
                $relations[] = 'timeSlot';
            }

            if ($relations !== []) {
                $booking->loadMissing($relations);
            }

            Notification::route('mail', $adminEmail)
                ->notify(new BookingRescheduleRequestedAdminNotification($booking));
        }

        return back()->with('success', 'Your reschedule request has been sent.');
    }
}
