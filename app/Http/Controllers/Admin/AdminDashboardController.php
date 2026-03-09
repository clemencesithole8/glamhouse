<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $weekEnd = Carbon::today()->addDays(7);

        return view('admin.dashboard', [
            'todayBookings' => Booking::whereDate('appointment_date', $today)->count(),
            'weekBookings' => Booking::whereBetween('appointment_date', [$today, $weekEnd])->count(),
            'pending' => Booking::where('status','pending')->count(),
            'revenueThisMonth' => Payment::whereMonth('paid_at', now()->month)->whereYear('paid_at', now()->year)->sum('amount'),
        ]);
    }
}
