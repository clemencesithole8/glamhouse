<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $bookings = collect();

        if (Schema::hasTable('bookings')) {
            $hasServices = Schema::hasTable('services');
            $hasTimeSlots = Schema::hasTable('time_slots');
            $hasPayments = Schema::hasTable('payments');

            $relations = [];

            if ($hasServices) {
                $relations[] = 'service';
            }

            if ($hasTimeSlots) {
                $relations[] = 'timeSlot';
            }

            if ($hasPayments) {
                $relations[] = 'payments';
            }

            $query = Booking::query()
                ->where('email', $user->email)
                ->orderByRaw('appointment_date >= ? desc', [now()->toDateString()])
                ->orderBy('appointment_date')
                ->latest('created_at');

            if ($relations !== []) {
                $query->with($relations);
            }

            $bookings = $query->get();

            $bookings->each(fn (Booking $booking) => $this->setMissingRelationDefaults(
                $booking,
                $hasServices,
                $hasTimeSlots,
                $hasPayments,
            ));
        }

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
}
