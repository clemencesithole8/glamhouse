@extends('layouts.public')
@section('title', 'Portfolio - Glamhouse')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-12">
    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="font-serif text-4xl">Portfolio</h1>
            <p class="mt-3 text-black/70 max-w-2xl">
                A selection of looks across soft glam, natural glam, full glam, and media-ready work.
            </p>
        </div>
        <a href="{{ route('booking.create') }}"
           class="rounded-full bg-black px-6 py-3 text-white hover:bg-rosegold-600 transition w-fit">
            Book a Look
        </a>
    </div>

    {{-- You can swap this banner anytime --}}
    <div class="mt-8 rounded-3xl border border-black/10 overflow-hidden bg-white">
        <img src="{{ asset('images/portfolio-banner.jpg') }}" alt="Portfolio Banner"
             class="w-full h-[260px] object-cover">
    </div>

    <div class="mt-10 grid gap-4 sm:grid-cols-2 md:grid-cols-3">
        @forelse($items as $item)
            <div class="rounded-2xl border border-black/10 overflow-hidden bg-white">
                <img src="{{ asset('storage/'.$item->image_path) }}"
                     alt="{{ $item->title ?? 'Portfolio Image' }}"
                     class="w-full h-64 object-cover">
                <div class="p-4">
                    <div class="font-semibold">{{ $item->title ?? 'Look' }}</div>
                    <div class="text-sm text-black/60">{{ $item->category ?? 'Category' }}</div>
                </div>
            </div>
        @empty
            {{-- Placeholder cards until you start uploading --}}
            @for($i=0; $i<6; $i++)
                <div class="rounded-2xl border border-black/10 overflow-hidden bg-white">
                    <img src="{{ asset('images/portfolio-placeholder.jpg') }}" alt="Placeholder"
                         class="w-full h-64 object-cover">
                    <div class="p-4">
                        <div class="font-semibold">Add your images</div>
                        <div class="text-sm text-black/60">Upload portfolio items in admin later</div>
                    </div>
                </div>
            @endfor
        @endforelse
    </div>

    <div class="mt-10">
        {{ $items->links() }}
    </div>
</div>
@endsection