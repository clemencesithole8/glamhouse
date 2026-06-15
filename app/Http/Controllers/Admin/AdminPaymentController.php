<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\Payment;
use App\Notifications\PaymentRecordedClientNotification;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Throwable;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $monthRange = $this->monthRange($request);

        if (! Schema::hasTable('payments')) {
            return view('admin.payments.index', [
                'payments' => $this->emptyPaginator($request, 30),
                'total' => 0,
                'selectedMonth' => $monthRange['label'],
                'monthlyTotal' => 0,
                'monthlyPaymentCount' => 0,
                'revenueByMonth' => collect(),
                'methodSummaries' => collect(),
                'outstandingBookings' => collect(),
                'outstandingTotal' => 0,
            ]);
        }

        $hasBookings = Schema::hasTable('bookings');
        $q = $this->filteredPaymentQuery($request, $hasBookings);

        $payments = $q->paginate(30)->withQueryString();

        if (! $hasBookings) {
            $payments->getCollection()->each(fn (Payment $payment) => $payment->setRelation('booking', null));
        }

        $monthlyQuery = Payment::query()
            ->whereBetween('paid_at', [$monthRange['start'], $monthRange['end']]);

        [$outstandingBookings, $outstandingTotal] = $this->outstandingBalances();

        return view('admin.payments.index', [
            'payments' => $payments,
            'total' => (clone $q)->sum('amount'),
            'selectedMonth' => $monthRange['label'],
            'monthlyTotal' => (clone $monthlyQuery)->sum('amount'),
            'monthlyPaymentCount' => (clone $monthlyQuery)->count(),
            'revenueByMonth' => $this->revenueByMonth(),
            'methodSummaries' => $this->methodSummaries($monthRange['start'], $monthRange['end']),
            'outstandingBookings' => $outstandingBookings,
            'outstandingTotal' => $outstandingTotal,
        ]);
    }

    public function exportCsv(Request $request)
    {
        if (! Schema::hasTable('payments')) {
            abort(404);
        }

        $hasBookings = Schema::hasTable('bookings');
        $payments = $this->filteredPaymentQuery($request, $hasBookings)
            ->get();

        $filename = 'glamhouse-payments-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($payments, $hasBookings): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Payment ID',
                'Paid At',
                'Client',
                'Email',
                'Phone',
                'Booking Date',
                'Type',
                'Amount',
                'Method',
                'Reference',
                'Booking Total',
                'Balance Due',
                'Payment Status',
            ]);

            foreach ($payments as $payment) {
                $booking = $hasBookings ? $payment->booking : null;

                fputcsv($handle, [
                    $payment->id,
                    optional($payment->paid_at)->format('Y-m-d H:i:s'),
                    $booking?->full_name,
                    $booking?->email,
                    $booking?->phone,
                    optional($booking?->appointment_date)->format('Y-m-d'),
                    $payment->type,
                    $payment->amount,
                    $payment->method,
                    $payment->reference,
                    $booking?->total_amount,
                    $booking?->balance_due,
                    $booking?->payment_status_label,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
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

        $payment = $booking->payments()->create([
            ...$data,
            'paid_at' => now(),
        ]);

        $receiptSent = false;

        if ($booking->email) {
            try {
                $relations = ['payments'];

                if (Schema::hasTable('services')) {
                    $relations[] = 'service';
                }

                if (Schema::hasTable('time_slots')) {
                    $relations[] = 'timeSlot';
                }

                $booking->loadMissing($relations);
                $booking->notify(new PaymentRecordedClientNotification($payment));
                $receiptSent = true;
            } catch (Throwable $exception) {
                report($exception);
            }
        }

        AuditLog::record('payment.created', $payment, [], $payment->toArray(), 'Payment recorded for booking #'.$booking->id.'.');

        return back()->with('success', $receiptSent
            ? 'Payment recorded and receipt emailed to the client.'
            : 'Payment recorded. No receipt email was sent because the booking has no email address or mail delivery failed.');
    }

    private function filteredPaymentQuery(Request $request, bool $withBooking)
    {
        $q = Payment::query()->latest();

        if ($withBooking) {
            $relations = ['booking.payments'];

            if (Schema::hasTable('services')) {
                $relations[] = 'booking.service';
            }

            $q->with($relations);
        }

        if ($request->filled('from')) $q->whereDate('paid_at', '>=', $request->date('from'));
        if ($request->filled('to')) $q->whereDate('paid_at', '<=', $request->date('to'));
        if ($request->filled('method')) $q->where('method', $request->string('method'));

        return $q;
    }

    private function monthRange(Request $request): array
    {
        $month = (string) $request->input('month', now()->format('Y-m'));

        try {
            $start = Carbon::createFromFormat('Y-m-d', $month.'-01')->startOfMonth();
        } catch (Throwable) {
            $start = now()->startOfMonth();
        }

        return [
            'label' => $start->format('Y-m'),
            'start' => $start->copy()->startOfMonth(),
            'end' => $start->copy()->endOfMonth(),
        ];
    }

    private function revenueByMonth()
    {
        return collect(range(5, 0))->map(function (int $monthsAgo) {
            $start = now()->subMonths($monthsAgo)->startOfMonth();
            $end = $start->copy()->endOfMonth();

            return [
                'label' => $start->format('M Y'),
                'total' => Payment::query()->whereBetween('paid_at', [$start, $end])->sum('amount'),
            ];
        });
    }

    private function methodSummaries(Carbon $start, Carbon $end)
    {
        return Payment::query()
            ->whereBetween('paid_at', [$start, $end])
            ->get()
            ->groupBy(fn (Payment $payment) => trim((string) $payment->method) ?: 'Not set')
            ->map(fn ($payments, string $method) => [
                'method' => $method,
                'records' => $payments->count(),
                'total' => $payments->sum('amount'),
            ])
            ->sortByDesc('total')
            ->values();
    }

    private function outstandingBalances(): array
    {
        if (! Schema::hasTable('bookings') || ! Schema::hasTable('payments')) {
            return [collect(), 0];
        }

        $relations = ['payments'];

        if (Schema::hasTable('services')) {
            $relations[] = 'service';
        }

        $bookings = Booking::query()
            ->with($relations)
            ->whereNotNull('total_amount')
            ->where('status', '!=', 'cancelled')
            ->orderBy('appointment_date')
            ->get();

        if (! Schema::hasTable('services')) {
            $bookings->each(fn (Booking $booking) => $booking->setRelation('service', null));
        }

        $outstanding = $bookings
            ->filter(fn (Booking $booking) => (int) $booking->balance_due > 0)
            ->values();

        return [
            $outstanding->take(8),
            $outstanding->sum(fn (Booking $booking) => (int) $booking->balance_due),
        ];
    }

    private function emptyPaginator(Request $request, int $perPage): LengthAwarePaginator
    {
        $paginator = new LengthAwarePaginator([], 0, $perPage, $request->integer('page', 1), [
            'path' => $request->url(),
        ]);

        return $paginator->appends($request->query());
    }
}
