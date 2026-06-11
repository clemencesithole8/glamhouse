<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $weekEnd = Carbon::today()->addDays(7);
        $hasBookings = Schema::hasTable('bookings');
        $hasPayments = Schema::hasTable('payments');

        return view('admin.dashboard', [
            'todayBookings' => $hasBookings ? Booking::whereDate('appointment_date', $today)->count() : 0,
            'weekBookings' => $hasBookings ? Booking::whereBetween('appointment_date', [$today, $weekEnd])->count() : 0,
            'pending' => $hasBookings ? Booking::where('status','pending')->count() : 0,
            'revenueThisMonth' => $hasPayments ? Payment::whereMonth('paid_at', now()->month)->whereYear('paid_at', now()->year)->sum('amount') : 0,
        ]);
    }
}
