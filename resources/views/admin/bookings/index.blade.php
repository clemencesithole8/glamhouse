@extends('layouts.admin')

@section('title', 'Bookings - Glamhouse Admin')
@section('page_title', 'Bookings')

@section('page_actions')
    <a href="{{ route('admin.bookings.index') }}" class="btn-outline text-xs">Reset Filters</a>
    <a href="{{ route('admin.payments.index') }}" class="btn-primary text-xs">View Payments</a>
@endsection

@section('content')
<div class="space-y-5">
    <section class="rounded-3xl border border-black/10 bg-white p-5">
        <form method="GET" class="grid gap-3 md:grid-cols-[1fr_auto_auto_auto]">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search name, phone, or email"
                class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500"
            >

            <select name="status" class="rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
                <option value="">All statuses</option>
                @foreach (\App\Models\Booking::STATUSES as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>

            <input
                type="date"
                name="date"
                value="{{ request('date') }}"
                class="rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500"
            >

            <button class="btn-primary text-sm">Filter</button>
        </form>
    </section>

    <section class="overflow-hidden rounded-3xl border border-black/10 bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-[#f8f2ec] text-left text-xs uppercase tracking-[0.12em] text-black/60">
                    <tr>
                        <th class="px-4 py-3">Client</th>
                        <th class="px-4 py-3">Appointment</th>
                        <th class="px-4 py-3">Service</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Payment</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        @php
                            $badgeClasses = match ($booking->status) {
                                'pending' => 'bg-amber-100 text-amber-900',
                                'reviewed' => 'bg-violet-100 text-violet-900',
                                'confirmed' => 'bg-sky-100 text-sky-900',
                                'completed' => 'bg-emerald-100 text-emerald-900',
                                'cancelled' => 'bg-rose-100 text-rose-900',
                                default => 'bg-gray-100 text-gray-800',
                            };
                        @endphp
                        <tr class="border-t border-black/10">
                            <td class="px-4 py-4 align-top">
                                <div class="font-semibold">{{ $booking->full_name }}</div>
                                <div class="mt-1 text-xs text-black/60">{{ $booking->phone }}</div>
                                @if ($booking->email)
                                    <div class="text-xs text-black/60">{{ $booking->email }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-4 align-top">
                                <div class="font-semibold">{{ optional($booking->appointment_date)->format('d M Y') }}</div>
                                <div class="mt-1 text-xs text-black/60">
                                    {{ $booking->timeSlot ? ($booking->timeSlot->start_time.' - '.$booking->timeSlot->end_time) : ($booking->preferred_time_text ?: 'Time pending') }}
                                </div>
                            </td>
                            <td class="px-4 py-4 align-top">
                                <div class="font-semibold">{{ $booking->service?->name ?? 'N/A' }}</div>
                                <div class="mt-1 text-xs text-black/60">{{ $booking->event_type ?: 'General booking' }}</div>
                            </td>
                            <td class="px-4 py-4 align-top">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $badgeClasses }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                                @if (str_contains((string) $booking->admin_notes, '[Notification alert]'))
                                    <div class="mt-2 inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-900">
                                        Alert issue
                                    </div>
                                @endif
                                @if ($booking->reschedule_requested_at)
                                    <div class="mt-2 text-xs font-semibold text-amber-700">Reschedule requested</div>
                                @endif
                            </td>
                            <td class="px-4 py-4 align-top">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $booking->payment_status_classes }}">
                                    {{ $booking->payment_status_label }}
                                </span>
                                <div class="mt-1 text-xs text-black/55">
                                    Paid ${{ number_format($booking->total_paid, 0) }}
                                </div>
                            </td>
                            <td class="px-4 py-4 text-right align-top">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn-outline inline-flex px-4 py-2 text-xs">Open</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-black/60">No bookings found for this filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($bookings->hasPages())
            <div class="border-t border-black/10 px-4 py-4">
                {{ $bookings->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
