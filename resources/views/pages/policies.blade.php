@extends('layouts.public')
@section('title', 'Policies - Glamhouse')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-12">
    <h1 class="font-serif text-4xl">Booking Policies</h1>
    <p class="mt-3 text-black/70">Please review before submitting your booking.</p>

    <div class="mt-8 rounded-3xl border border-black/10 overflow-hidden bg-white">
        <img src="{{ asset('images/policies-banner.jpg') }}" alt="Policies"
             class="w-full h-[220px] object-cover">
    </div>

    <div class="mt-8 space-y-6">
        <div class="rounded-2xl border border-black/10 bg-white p-6">
            <h2 class="font-semibold">Bookings & Confirmation</h2>
            <ul class="mt-3 text-sm text-black/70 list-disc pl-5 space-y-1">
                <li>All bookings must be made in advance via DM or booking form.</li>
                <li>A non-refundable retainer (deposit) is required to secure your date and time.</li>
                <li>Your booking is only confirmed after the retainer is paid.</li>
            </ul>
        </div>

        <div class="rounded-2xl border border-black/10 bg-white p-6">
            <h2 class="font-semibold">Payment</h2>
            <ul class="mt-3 text-sm text-black/70 list-disc pl-5 space-y-1">
                <li>Prices depend on the selected makeup package and location.</li>
                <li>Balance must be paid before or on the day of the appointment.</li>
                <li>Transport fees apply for outcall services and vary by location.</li>
            </ul>
        </div>

        <div class="rounded-2xl border border-black/10 bg-white p-6">
            <h2 class="font-semibold">Time & Punctuality</h2>
            <ul class="mt-3 text-sm text-black/70 list-disc pl-5 space-y-1">
                <li>Please be ready at your scheduled time.</li>
                <li>Late arrival may shorten your makeup time or lead to cancellation without refund.</li>
            </ul>
        </div>

        <div class="rounded-2xl border border-black/10 bg-white p-6">
            <h2 class="font-semibold">Cancellations & Rescheduling</h2>
            <ul class="mt-3 text-sm text-black/70 list-disc pl-5 space-y-1">
                <li>Retainers are non-refundable.</li>
                <li>Rescheduling must be done at least 24 hours in advance (subject to availability).</li>
                <li>Same-day cancellations forfeit the retainer.</li>
            </ul>
        </div>

        <div class="rounded-2xl border border-black/10 bg-white p-6">
            <h2 class="font-semibold">Media & Content</h2>
            <ul class="mt-3 text-sm text-black/70 list-disc pl-5 space-y-1">
                <li>Photos/videos may be taken for portfolio and marketing purposes.</li>
                <li>Clients must sign a media release form to give or deny consent.</li>
                <li>No content will be posted without client consent.</li>
            </ul>
        </div>
    </div>
</div>
@endsection