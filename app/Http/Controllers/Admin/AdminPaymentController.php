<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        if (! Schema::hasTable('payments')) {
            return view('admin.payments.index', [
                'payments' => $this->emptyPaginator($request, 30),
                'total' => 0,
            ]);
        }

        $hasBookings = Schema::hasTable('bookings');
        $q = Payment::query()->latest();

        if ($hasBookings) {
            $q->with('booking');
        }

        if ($request->filled('from')) $q->whereDate('paid_at', '>=', $request->date('from'));
        if ($request->filled('to')) $q->whereDate('paid_at', '<=', $request->date('to'));

        $payments = $q->paginate(30)->withQueryString();

        if (! $hasBookings) {
            $payments->getCollection()->each(fn (Payment $payment) => $payment->setRelation('booking', null));
        }

        return view('admin.payments.index', [
            'payments' => $payments,
            'total' => (clone $q)->sum('amount'),
        ]);
    }

    public function store(Request $request, string $bookingId)
    {
        abort_unless(Schema::hasTable('bookings'), 404);

        if (! Schema::hasTable('payments')) {
            return back()
                ->withInput()
                ->withErrors(['amount' => 'Payment recording is still being prepared. Please try again shortly.']);
        }

        $booking = Booking::query()->findOrFail($bookingId);

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

    private function emptyPaginator(Request $request, int $perPage): LengthAwarePaginator
    {
        $paginator = new LengthAwarePaginator([], 0, $perPage, $request->integer('page', 1), [
            'path' => $request->url(),
        ]);

        return $paginator->appends($request->query());
    }
}
