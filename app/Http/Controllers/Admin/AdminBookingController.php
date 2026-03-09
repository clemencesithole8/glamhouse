<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $q = Booking::query()->with(['service','timeSlot'])->latest();

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

        return view('admin.bookings.index', [
            'bookings' => $q->paginate(20)->withQueryString(),
        ]);
    }

    public function show(Booking $booking)
    {
        return view('admin.bookings.show', [
            'booking' => $booking->load(['service','timeSlot','payments']),
        ]);
    }

    public function updateStatus(Request $request, Booking $booking)
    {
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

    public function cancel(Booking $booking)
    {
        $booking->update([
            'status' => 'cancelled',
            'time_slot_id' => null,
        ]);

        return back()->with('success', 'Booking cancelled and slot freed.');
    }
}