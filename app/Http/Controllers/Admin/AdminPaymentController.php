<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $q = Payment::query()->with('booking')->latest();

        if ($request->filled('from')) $q->whereDate('paid_at', '>=', $request->date('from'));
        if ($request->filled('to')) $q->whereDate('paid_at', '<=', $request->date('to'));

        return view('admin.payments.index', [
            'payments' => $q->paginate(30)->withQueryString(),
            'total' => (clone $q)->sum('amount'),
        ]);
    }

    public function store(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'type' => ['required','in:deposit,balance,full'],
            'amount' => ['required','integer','min:1'],
            'method' => ['nullable','string','max:50'],
            'reference' => ['nullable','string','max:100'],
        ]);

        $booking->payments()->create([
            ...$data,
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Payment recorded.');
    }
}