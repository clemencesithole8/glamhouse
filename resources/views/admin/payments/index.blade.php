@extends('layouts.admin')

@section('title', 'Payments - Glamhouse Admin')
@section('page_title', 'Payments')

@section('page_actions')
    <a href="{{ route('admin.bookings.index') }}" class="btn-outline text-xs">Open Bookings</a>
    <a href="{{ route('admin.payments.export', request()->query()) }}" class="btn-primary text-xs">Export CSV</a>
@endsection

@section('content')
<div class="space-y-5">
    <section class="rounded-3xl border border-black/10 bg-white p-5">
        <form method="GET" class="grid gap-3 md:grid-cols-[auto_auto_auto_auto_auto] md:items-end">
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Report Month</label>
                <input type="month" name="month" value="{{ $selectedMonth }}" class="rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">From</label>
                <input type="date" name="from" value="{{ request('from') }}" class="rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">To</label>
                <input type="date" name="to" value="{{ request('to') }}" class="rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Method</label>
                <input type="text" name="method" value="{{ request('method') }}" placeholder="Cash / Bank" class="rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div class="flex gap-2">
                <button class="btn-primary text-sm">Filter</button>
                <a href="{{ route('admin.payments.index') }}" class="btn-outline text-sm">Reset</a>
            </div>
        </form>
    </section>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <article class="glass-card rounded-3xl p-5">
            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-black/55">Filtered Total</div>
            <div class="mt-3 text-4xl font-extrabold text-[#2b1d19]">${{ number_format($total, 0) }}</div>
            <p class="mt-2 text-sm text-black/60">Sum of payments in current filter range.</p>
        </article>
        <article class="glass-card rounded-3xl p-5">
            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-black/55">Selected Month</div>
            <div class="mt-3 text-4xl font-extrabold text-[#2b1d19]">${{ number_format($monthlyTotal, 0) }}</div>
            <p class="mt-2 text-sm text-black/60">{{ \Illuminate\Support\Carbon::createFromFormat('Y-m', $selectedMonth)->format('F Y') }} revenue from {{ $monthlyPaymentCount }} payment(s).</p>
        </article>
        <article class="glass-card rounded-3xl p-5">
            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-black/55">Records</div>
            <div class="mt-3 text-4xl font-extrabold text-[#2b1d19]">{{ $payments->total() }}</div>
            <p class="mt-2 text-sm text-black/60">Total payment entries available.</p>
        </article>
        <article class="glass-card rounded-3xl p-5">
            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-black/55">Outstanding</div>
            <div class="mt-3 text-4xl font-extrabold text-[#2b1d19]">${{ number_format($outstandingTotal, 0) }}</div>
            <p class="mt-2 text-sm text-black/60">Known balances from quoted bookings.</p>
        </article>
    </section>

    <section class="grid gap-4 lg:grid-cols-2">
        <article class="rounded-3xl border border-black/10 bg-white p-5">
            <div class="flex items-center justify-between gap-3">
                <h2 class="font-display text-2xl text-[#2a1c19]">Monthly Revenue</h2>
                <span class="text-xs uppercase tracking-[0.14em] text-black/50">Last 6 months</span>
            </div>
            <div class="mt-4 space-y-3">
                @forelse ($revenueByMonth as $month)
                    @php
                        $max = max(1, $revenueByMonth->max('total'));
                        $width = max(4, ((int) $month['total'] / $max) * 100);
                    @endphp
                    <div>
                        <div class="mb-1 flex items-center justify-between text-sm">
                            <span class="font-semibold">{{ $month['label'] }}</span>
                            <span>${{ number_format($month['total'], 0) }}</span>
                        </div>
                        <div class="h-2 rounded-full bg-[#f4e9df]">
                            <div class="h-2 rounded-full bg-rosegold-500" style="width: {{ $width }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-black/15 px-4 py-4 text-sm text-black/60">No revenue data yet.</div>
                @endforelse
            </div>
        </article>

        <article class="rounded-3xl border border-black/10 bg-white p-5">
            <div class="flex items-center justify-between gap-3">
                <h2 class="font-display text-2xl text-[#2a1c19]">Payment Methods</h2>
                <span class="text-xs uppercase tracking-[0.14em] text-black/50">{{ \Illuminate\Support\Carbon::createFromFormat('Y-m', $selectedMonth)->format('M Y') }}</span>
            </div>
            <div class="mt-4 space-y-2">
                @forelse ($methodSummaries as $summary)
                    <div class="flex items-center justify-between rounded-xl bg-[#fbf7f3] px-4 py-3 text-sm">
                        <div>
                            <div class="font-semibold">{{ $summary['method'] }}</div>
                            <div class="text-xs text-black/55">{{ $summary['records'] }} payment(s)</div>
                        </div>
                        <div class="font-bold">${{ number_format($summary['total'], 0) }}</div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-black/15 px-4 py-4 text-sm text-black/60">No payment methods recorded for this month.</div>
                @endforelse
            </div>
        </article>
    </section>

    <section class="rounded-3xl border border-black/10 bg-white p-5">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="font-display text-2xl text-[#2a1c19]">Outstanding Balances</h2>
                <p class="mt-1 text-sm text-black/60">Top quoted bookings with money still due.</p>
            </div>
            <div class="text-sm font-semibold text-[#2a1c19]">Total: ${{ number_format($outstandingTotal, 0) }}</div>
        </div>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="text-left text-xs uppercase tracking-[0.12em] text-black/55">
                    <tr>
                        <th class="py-2 pr-4">Client</th>
                        <th class="py-2 pr-4">Service</th>
                        <th class="py-2 pr-4">Appointment</th>
                        <th class="py-2 pr-4">Status</th>
                        <th class="py-2 text-right">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($outstandingBookings as $booking)
                        <tr class="border-t border-black/10">
                            <td class="py-3 pr-4">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="font-semibold text-[#8f3a4e] hover:text-[#241915]">{{ $booking->full_name }}</a>
                                <div class="text-xs text-black/55">{{ $booking->phone }}</div>
                            </td>
                            <td class="py-3 pr-4">{{ $booking->service?->name ?? 'N/A' }}</td>
                            <td class="py-3 pr-4">{{ optional($booking->appointment_date)->format('d M Y') }}</td>
                            <td class="py-3 pr-4">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $booking->payment_status_classes }}">{{ $booking->payment_status_label }}</span>
                            </td>
                            <td class="py-3 text-right font-bold">${{ number_format($booking->balance_due, 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-sm text-black/60">No outstanding balances found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="overflow-hidden rounded-3xl border border-black/10 bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-[#f8f2ec] text-left text-xs uppercase tracking-[0.12em] text-black/60">
                    <tr>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Client</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Method / Ref</th>
                        <th class="px-4 py-3">Receipt</th>
                        <th class="px-4 py-3 text-right">Booking</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr class="border-t border-black/10">
                            <td class="px-4 py-4">{{ optional($payment->paid_at)->format('d M Y H:i') ?: '-' }}</td>
                            <td class="px-4 py-4">
                                <div class="font-semibold">{{ $payment->booking?->full_name ?? 'Deleted booking' }}</div>
                                <div class="text-xs text-black/60">{{ $payment->booking?->phone }}</div>
                            </td>
                            <td class="px-4 py-4"><span class="rounded-full bg-rosegold-100 px-2.5 py-1 text-xs font-semibold text-rosegold-800">{{ ucfirst($payment->type) }}</span></td>
                            <td class="px-4 py-4 font-bold">${{ number_format($payment->amount, 0) }}</td>
                            <td class="px-4 py-4 text-xs text-black/70">
                                <div>{{ $payment->method ?: 'Not set' }}</div>
                                <div>{{ $payment->reference ?: '-' }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <a href="{{ route('payment.receipt', $payment) }}" class="text-xs font-semibold text-[#8f3a4e] hover:text-[#241915]">PDF</a>
                            </td>
                            <td class="px-4 py-4 text-right">
                                @if ($payment->booking)
                                    <a href="{{ route('admin.bookings.show', $payment->booking) }}" class="btn-outline inline-flex px-4 py-2 text-xs">Open</a>
                                @else
                                    <span class="text-xs text-black/50">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-sm text-black/60">No payments found for this filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($payments->hasPages())
            <div class="border-t border-black/10 px-4 py-4">
                {{ $payments->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
