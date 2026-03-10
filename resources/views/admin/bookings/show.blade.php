@extends('layouts.admin')

@section('title', 'Booking Details - Glamhouse Admin')
@section('page_title', 'Booking Details')

@section('page_actions')
    <a href="{{ route('admin.bookings.index') }}" class="btn-outline text-xs">Back to Bookings</a>
    <a href="{{ route('booking.pdf', $booking) }}" class="btn-primary text-xs">Download PDF</a>
@endsection

@section('content')
@php
    $statusClasses = match ($booking->status) {
        'pending' => 'bg-amber-100 text-amber-900',
        'confirmed' => 'bg-sky-100 text-sky-900',
        'completed' => 'bg-emerald-100 text-emerald-900',
        'cancelled' => 'bg-rose-100 text-rose-900',
        default => 'bg-gray-100 text-gray-800',
    };

    $totalPaid = $booking->payments->sum('amount');
    $balance = $booking->total_amount !== null ? max(0, $booking->total_amount - $totalPaid) : null;
@endphp

<div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
    <div class="space-y-6">
        <section class="rounded-3xl border border-black/10 bg-white p-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="font-display text-3xl text-[#2a1c19]">{{ $booking->full_name }}</h2>
                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">{{ ucfirst($booking->status) }}</span>
            </div>

            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div>
                    <div class="text-xs uppercase tracking-[0.15em] text-black/50">Phone</div>
                    <div class="mt-1 font-semibold">{{ $booking->phone }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-[0.15em] text-black/50">Email</div>
                    <div class="mt-1 font-semibold">{{ $booking->email ?: 'Not provided' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-[0.15em] text-black/50">Date</div>
                    <div class="mt-1 font-semibold">{{ optional($booking->appointment_date)->format('D, d M Y') }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-[0.15em] text-black/50">Time</div>
                    <div class="mt-1 font-semibold">
                        {{ $booking->timeSlot ? ($booking->timeSlot->start_time.' - '.$booking->timeSlot->end_time) : ($booking->preferred_time_text ?: 'Pending') }}
                    </div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-[0.15em] text-black/50">Service</div>
                    <div class="mt-1 font-semibold">{{ $booking->service?->name ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-[0.15em] text-black/50">Event Type</div>
                    <div class="mt-1 font-semibold">{{ $booking->event_type ?: 'General booking' }}</div>
                </div>
                <div class="sm:col-span-2">
                    <div class="text-xs uppercase tracking-[0.15em] text-black/50">Location / Area</div>
                    <div class="mt-1 font-semibold">{{ $booking->location_area }}</div>
                </div>
                @if ($booking->is_outcall && $booking->outcall_address)
                    <div class="sm:col-span-2">
                        <div class="text-xs uppercase tracking-[0.15em] text-black/50">Outcall Address</div>
                        <div class="mt-1">{{ $booking->outcall_address }}</div>
                    </div>
                @endif
            </div>
        </section>

        <section class="rounded-3xl border border-black/10 bg-white p-6">
            <h3 class="font-display text-2xl text-[#2a1c19]">Booking Controls</h3>

            <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" class="mt-4 grid gap-3 md:grid-cols-[1fr_auto]">
                @csrf
                <select name="status" class="rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
                    @foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $status)
                        <option value="{{ $status }}" @selected($booking->status === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <button class="btn-primary text-sm">Update Status</button>
            </form>

            <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}" class="mt-3" onsubmit="return confirm('Cancel this booking and free the slot?');">
                @csrf
                <button class="rounded-xl border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-800 transition hover:bg-rose-50">
                    Cancel Booking and Free Slot
                </button>
            </form>

            <div class="mt-6 border-t border-black/10 pt-5">
                <h4 class="font-semibold">Record Payment</h4>
                <form method="POST" action="{{ route('admin.payments.store', $booking) }}" class="mt-3 grid gap-3 md:grid-cols-2">
                    @csrf
                    <div>
                        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Type</label>
                        <select name="type" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
                            <option value="deposit">Deposit</option>
                            <option value="balance">Balance</option>
                            <option value="full">Full</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Amount</label>
                        <input type="number" name="amount" min="1" required class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" placeholder="Amount">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Method</label>
                        <input type="text" name="method" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" placeholder="Cash / Bank / Transfer">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Reference</label>
                        <input type="text" name="reference" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" placeholder="Transaction ID">
                    </div>
                    <div class="md:col-span-2">
                        <button class="btn-primary text-sm">Save Payment</button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <div class="space-y-6">
        <section class="rounded-3xl border border-black/10 bg-white p-6">
            <h3 class="font-display text-2xl text-[#2a1c19]">Financial Snapshot</h3>

            <div class="mt-4 space-y-3 text-sm">
                <div class="flex items-center justify-between rounded-xl bg-[#f8f2ec] px-4 py-3">
                    <span>Total Amount</span>
                    <span class="font-bold">{{ $booking->total_amount !== null ? '$'.number_format($booking->total_amount, 0) : 'Not set' }}</span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-[#f8f2ec] px-4 py-3">
                    <span>Deposit Amount</span>
                    <span class="font-bold">{{ $booking->deposit_amount !== null ? '$'.number_format($booking->deposit_amount, 0) : 'Not set' }}</span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-[#f8f2ec] px-4 py-3">
                    <span>Total Paid</span>
                    <span class="font-bold">${{ number_format($totalPaid, 0) }}</span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-[#f8f2ec] px-4 py-3">
                    <span>Balance Due</span>
                    <span class="font-bold">{{ $balance !== null ? '$'.number_format($balance, 0) : 'N/A' }}</span>
                </div>
            </div>

            <h4 class="mt-6 font-semibold">Payment History</h4>
            <div class="mt-3 space-y-2">
                @forelse ($booking->payments as $payment)
                    <div class="rounded-xl border border-black/10 px-4 py-3 text-sm">
                        <div class="flex items-center justify-between">
                            <div class="font-semibold">{{ ucfirst($payment->type) }} - ${{ number_format($payment->amount, 0) }}</div>
                            <div class="text-xs text-black/55">{{ optional($payment->paid_at)->format('d M Y H:i') ?: 'Date pending' }}</div>
                        </div>
                        <div class="mt-1 text-xs text-black/60">{{ $payment->method ?: 'Method not set' }}{{ $payment->reference ? ' | Ref: '.$payment->reference : '' }}</div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-black/15 px-4 py-4 text-sm text-black/60">No payments recorded yet.</div>
                @endforelse
            </div>
        </section>

        <section class="rounded-3xl border border-black/10 bg-white p-6">
            <h3 class="font-display text-2xl text-[#2a1c19]">Client Makeup Profile</h3>
            <div class="mt-4 space-y-3 text-sm">
                <div><span class="text-black/55">Skin Type:</span> <span class="font-semibold">{{ $booking->skin_type ?: 'Not provided' }}</span></div>
                <div><span class="text-black/55">Allergies / Notes:</span> <span class="font-semibold">{{ $booking->allergies_notes ?: 'None listed' }}</span></div>
                <div><span class="text-black/55">Done pro makeup before:</span> <span class="font-semibold">{{ $booking->has_done_pro_makeup ? 'Yes' : 'No' }}</span></div>
                <div><span class="text-black/55">Client confirmed details:</span> <span class="font-semibold">{{ $booking->info_confirmed ? 'Yes' : 'No' }}</span></div>
                <div><span class="text-black/55">Deposit policy acknowledged:</span> <span class="font-semibold">{{ $booking->deposit_ack ? 'Yes' : 'No' }}</span></div>
                <div><span class="text-black/55">Lateness policy acknowledged:</span> <span class="font-semibold">{{ $booking->lateness_ack ? 'Yes' : 'No' }}</span></div>
            </div>

            @if ($booking->reference_image_path)
                <div class="mt-5">
                    <div class="mb-2 text-xs uppercase tracking-[0.13em] text-black/55">Reference Look</div>
                    <img src="{{ asset('storage/'.$booking->reference_image_path) }}" alt="Reference look" class="w-full rounded-2xl border border-black/10 object-cover">
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
