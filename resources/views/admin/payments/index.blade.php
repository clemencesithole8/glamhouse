@extends('layouts.admin')

@section('title', 'Payments - Glamhouse Admin')
@section('page_title', 'Payments')

@section('page_actions')
    <a href="{{ route('admin.bookings.index') }}" class="btn-outline text-xs">Open Bookings</a>
@endsection

@section('content')
<div class="space-y-5">
    <section class="rounded-3xl border border-black/10 bg-white p-5">
        <form method="GET" class="grid gap-3 md:grid-cols-[auto_auto_auto] md:items-end">
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">From</label>
                <input type="date" name="from" value="{{ request('from') }}" class="rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">To</label>
                <input type="date" name="to" value="{{ request('to') }}" class="rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div class="flex gap-2">
                <button class="btn-primary text-sm">Filter</button>
                <a href="{{ route('admin.payments.index') }}" class="btn-outline text-sm">Reset</a>
            </div>
        </form>
    </section>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <article class="glass-card rounded-3xl p-5">
            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-black/55">Filtered Total</div>
            <div class="mt-3 text-4xl font-extrabold text-[#2b1d19]">${{ number_format($total, 0) }}</div>
            <p class="mt-2 text-sm text-black/60">Sum of payments in current filter range.</p>
        </article>
        <article class="glass-card rounded-3xl p-5">
            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-black/55">Records</div>
            <div class="mt-3 text-4xl font-extrabold text-[#2b1d19]">{{ $payments->total() }}</div>
            <p class="mt-2 text-sm text-black/60">Total payment entries available.</p>
        </article>
        <article class="glass-card rounded-3xl p-5">
            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-black/55">Latest Entry</div>
            <div class="mt-3 text-lg font-bold text-[#2b1d19]">{{ optional(optional($payments->first())->paid_at)->format('d M Y H:i') ?: 'No entries' }}</div>
            <p class="mt-2 text-sm text-black/60">Most recent recorded payment.</p>
        </article>
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
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-black/60">No payments found for this filter.</td>
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
