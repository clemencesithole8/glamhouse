@extends('layouts.public')
@section('title', 'Services - Glamhouse')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-12">
    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="font-serif text-4xl">Packages</h1>
            <p class="mt-3 text-black/70 max-w-2xl">
                Skincare-first, feature-enhancing makeup designed to last and look flawless in real conditions —
                from events to professional media.
            </p>
        </div>
        <a href="{{ route('booking.create') }}"
           class="rounded-full bg-black px-6 py-3 text-white hover:bg-rosegold-600 transition w-fit">
            Book Now
        </a>
    </div>

    {{-- Image banner --}}
    <div class="mt-8 rounded-3xl border border-black/10 overflow-hidden bg-white">
        <img src="{{ asset('images/services-banner.jpg') }}" alt="Services Banner"
             class="w-full h-[280px] object-cover">
    </div>

    <div class="mt-10 grid gap-4 md:grid-cols-2">
        @foreach($services as $service)
            <div class="rounded-2xl border border-black/10 bg-white p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="font-semibold text-lg">{{ $service->name }}</div>
                        <div class="mt-2 text-sm text-black/70">
                            {{ $service->description ?? 'Add description in the admin later.' }}
                        </div>
                        @if($service->is_consultation_based)
                            <div class="mt-3 inline-flex items-center rounded-full border border-rosegold-600/40 bg-rosegold-50 px-3 py-1 text-xs text-black">
                                Consultation-based
                            </div>
                        @endif
                    </div>
                    <div class="text-right font-semibold">
                        @if($service->price)
                            ${{ number_format($service->price, 0) }}
                        @else
                            Consult
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-12 rounded-2xl border border-black/10 bg-white p-6">
        <h2 class="font-semibold">Outcall & Payment Notes</h2>
        <ul class="mt-3 text-sm text-black/70 list-disc pl-5 space-y-1">
            <li>Transport fees apply for outcall services and vary by location.</li>
            <li>Balance must be paid before or on the day of the appointment.</li>
            <li>Deposit/retainer is required to secure date and time.</li>
        </ul>
    </div>
</div>
@endsection