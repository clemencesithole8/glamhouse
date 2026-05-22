<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $bookings = Booking::query()
            ->with(['service', 'timeSlot', 'payments'])
            ->where('email', $user->email)
            ->orderByRaw('appointment_date >= ? desc', [now()->toDateString()])
            ->orderBy('appointment_date')
            ->latest('created_at')
            ->get();

        $upcomingBookings = $bookings
            ->filter(fn (Booking $booking) => $booking->appointment_date->isToday() || $booking->appointment_date->isFuture())
            ->values();

        return view('dashboard', [
            'user' => $user,
            'bookings' => $bookings,
            'upcomingBookings' => $upcomingBookings,
            'nextBooking' => $upcomingBookings->first(),
            'pendingCount' => $bookings->where('status', 'pending')->count(),
            'confirmedCount' => $bookings->where('status', 'confirmed')->count(),
            'completedCount' => $bookings->where('status', 'completed')->count(),
        ]);
    }
}
