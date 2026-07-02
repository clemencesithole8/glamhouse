@extends('layouts.admin')

@section('title', 'Admin Dashboard - Glamhouse')
@section('page_title', 'Dashboard')

@section('page_actions')
    <a href="{{ route('admin.bookings.index') }}" class="btn-outline text-xs">Manage Bookings</a>
    <a href="{{ route('admin.media-assets.index') }}" class="btn-primary text-xs">Media Library</a>
@endsection

@section('content')
<div class="space-y-6">
    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <article class="glass-card rounded-3xl p-5">
            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-black/55">Today</div>
            <div class="mt-3 text-4xl font-extrabold text-[#2b1d19]">{{ $todayBookings }}</div>
            <p class="mt-2 text-sm text-black/60">Bookings scheduled for today.</p>
        </article>

        <article class="glass-card rounded-3xl p-5">
            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-black/55">Next 7 Days</div>
            <div class="mt-3 text-4xl font-extrabold text-[#2b1d19]">{{ $weekBookings }}</div>
            <p class="mt-2 text-sm text-black/60">Upcoming sessions this week.</p>
        </article>

        <article class="glass-card rounded-3xl p-5">
            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-black/55">Pending</div>
            <div class="mt-3 text-4xl font-extrabold text-rosegold-800">{{ $pending }}</div>
            <p class="mt-2 text-sm text-black/60">Bookings awaiting confirmation.</p>
        </article>

        <article class="glass-card rounded-3xl p-5">
            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-black/55">Revenue This Month</div>
            <div class="mt-3 text-4xl font-extrabold text-[#2b1d19]">${{ number_format($revenueThisMonth, 0) }}</div>
            <p class="mt-2 text-sm text-black/60">Payments recorded in current month.</p>
        </article>
    </section>

    <section class="grid gap-4 lg:grid-cols-2">
        <article class="rounded-3xl border border-black/10 bg-white p-6">
            <h2 class="font-display text-3xl text-[#2a1c19]">Daily Workflow</h2>
            <div class="mt-4 grid gap-3 text-sm">
                <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" class="rounded-xl border border-black/10 px-4 py-3 transition hover:bg-rosegold-50">
                    Review pending bookings
                </a>
                <a href="{{ route('admin.bookings.index', ['date' => now()->toDateString()]) }}" class="rounded-xl border border-black/10 px-4 py-3 transition hover:bg-rosegold-50">
                    Open today's schedule
                </a>
                <a href="{{ route('admin.payments.index') }}" class="rounded-xl border border-black/10 px-4 py-3 transition hover:bg-rosegold-50">
                    Check payments and totals
                </a>
                <a href="{{ route('admin.services.index') }}" class="rounded-xl border border-black/10 px-4 py-3 transition hover:bg-rosegold-50">
                    Update services and prices
                </a>
                <a href="{{ route('admin.availability-blocks.index') }}" class="rounded-xl border border-black/10 px-4 py-3 transition hover:bg-rosegold-50">
                    Manage unavailable dates and holidays
                </a>
                <a href="{{ route('admin.media-assets.index') }}" class="rounded-xl border border-black/10 px-4 py-3 transition hover:bg-rosegold-50">
                    Update homepage and portfolio images
                </a>
            </div>
        </article>

        <article class="rounded-3xl border border-black/10 bg-white p-6">
            <h2 class="font-display text-3xl text-[#2a1c19]">Studio Notes</h2>
            <ul class="mt-4 space-y-3 text-sm text-black/70">
                <li class="rounded-xl bg-[#fbf7f3] px-4 py-3">Confirm all pending clients 24 hours before appointment date.</li>
                <li class="rounded-xl bg-[#fbf7f3] px-4 py-3">Capture payment reference for every deposit to simplify month-end reconciliation.</li>
                <li class="rounded-xl bg-[#fbf7f3] px-4 py-3">Refresh key hero images in Media Assets seasonally to keep the brand current.</li>
                <li class="rounded-xl bg-[#fbf7f3] px-4 py-3">Set cancelled bookings to free up slots for rebooking opportunities.</li>
            </ul>
        </article>
    </section>
</div>
@endsection
