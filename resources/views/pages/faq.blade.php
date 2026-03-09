@extends('layouts.public')
@section('title', 'FAQ - Glamhouse')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-12">
    <h1 class="font-serif text-4xl">FAQs</h1>
    <p class="mt-3 text-black/70">Quick answers to common questions.</p>

    <div class="mt-8 rounded-3xl border border-black/10 overflow-hidden bg-white">
        <img src="{{ asset('images/faq-banner.jpg') }}" alt="FAQ"
             class="w-full h-[220px] object-cover">
    </div>

    <div class="mt-8 space-y-4">
        @php
            $faqs = [
                ['Do I need a deposit?', 'Yes. A non-refundable retainer (deposit) is required to secure your booking.'],
                ['Do you do outcalls?', 'Yes. Transport fees apply and vary by location.'],
                ['What if I’m late?', 'Late arrival may shorten your makeup time or lead to cancellation without refund.'],
                ['Do you offer bridal makeup?', 'This package list is for photoshoots, birthdays, and everyday makeup. Separate wedding packages apply for brides.'],
                ['Can I reschedule?', 'Yes—at least 24 hours in advance, subject to availability.'],
                ['Can you post my photos?', 'Only with your consent via a media release form.'],
            ];
        @endphp

        @foreach($faqs as [$q, $a])
            <details class="rounded-2xl border border-black/10 bg-white p-5">
                <summary class="cursor-pointer font-semibold">{{ $q }}</summary>
                <p class="mt-3 text-sm text-black/70">{{ $a }}</p>
            </details>
        @endforeach
    </div>
</div>
@endsection