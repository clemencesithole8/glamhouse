@extends('layouts.public')

@section('title', "Esther's Secrets - Glamhouse")

@section('content')
<div class="mx-auto max-w-6xl px-4 py-12 space-y-12">

    {{-- HERO SECTION --}}
    <section class="grid gap-8 md:grid-cols-2 items-center">
        <div>
            <p class="text-sm uppercase tracking-[0.2em] text-rosegold-600 font-medium">
                Skincare-first • Camera-aware • Long-lasting glam
            </p>

            <h1 class="mt-4 font-serif text-4xl md:text-6xl leading-tight">
                Esther's Secrets <span class="text-rosegold-600">Glamhouse</span>
            </h1>

            <p class="mt-5 max-w-2xl text-black/70 leading-relaxed">
                A skincare-focused, camera-aware makeup service that enhances natural features while delivering
                long-lasting, tailored glam for events, creatives, and corporate media.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('booking.create') }}"
                   class="rounded-full bg-black px-6 py-3 text-white hover:bg-rosegold-600 transition">
                    Book Now
                </a>

                <a href="{{ route('picturePerfect') }}"
                   class="rounded-full border border-black/20 px-6 py-3 hover:border-rosegold-600 hover:text-rosegold-600 transition">
                    Picture Perfect Package
                </a>
            </div>
        </div>

        <div class="rounded-3xl border border-black/10 overflow-hidden bg-white shadow-sm">
            <img
                src="{{ media_url('home_hero', asset('images/home-hero-fallback.jpg')) }}"
                alt="{{ media_alt('home_hero', 'Glamhouse hero image') }}"
                class="w-full h-[520px] object-cover"
            >
        </div>
    </section>

    {{-- USP STRIP --}}
    <section class="grid gap-4 md:grid-cols-4">
        <div class="rounded-2xl border border-black/10 p-5 bg-white">
            <div class="font-semibold">Skin health first</div>
            <div class="mt-2 text-sm text-black/70">
                Makeup that protects and enhances natural skin.
            </div>
        </div>

        <div class="rounded-2xl border border-black/10 p-5 bg-white">
            <div class="font-semibold">Feature-enhancing</div>
            <div class="mt-2 text-sm text-black/70">
                Beauty that complements facial anatomy, not masks it.
            </div>
        </div>

        <div class="rounded-2xl border border-black/10 p-5 bg-white">
            <div class="font-semibold">Camera-aware</div>
            <div class="mt-2 text-sm text-black/70">
                Looks designed to perform under lighting and lenses.
            </div>
        </div>

        <div class="rounded-2xl border border-black/10 p-5 bg-white">
            <div class="font-semibold">Long-lasting</div>
            <div class="mt-2 text-sm text-black/70">
                Glam that holds beautifully through heat, time, and events.
            </div>
        </div>
    </section>

    {{-- FEATURE IMAGE STRIP --}}
    <section class="grid gap-4 md:grid-cols-3">
        <div class="rounded-3xl border border-black/10 overflow-hidden bg-white">
            <img
                src="{{ media_url('home_feature_1', asset('images/home-feature-1.jpg')) }}"
                alt="{{ media_alt('home_feature_1', 'Soft glam makeup image') }}"
                class="w-full h-[260px] object-cover"
            >
        </div>

        <div class="rounded-3xl border border-black/10 overflow-hidden bg-white">
            <img
                src="{{ media_url('home_feature_2', asset('images/home-feature-2.jpg')) }}"
                alt="{{ media_alt('home_feature_2', 'Natural glam makeup image') }}"
                class="w-full h-[260px] object-cover"
            >
        </div>

        <div class="rounded-3xl border border-black/10 overflow-hidden bg-white">
            <img
                src="{{ media_url('home_feature_3', asset('images/home-feature-3.jpg')) }}"
                alt="{{ media_alt('home_feature_3', 'Full glam makeup image') }}"
                class="w-full h-[260px] object-cover"
            >
        </div>
    </section>

    {{-- ABOUT PREVIEW --}}
    <section class="grid gap-8 md:grid-cols-2 items-center">
        <div class="rounded-3xl border border-black/10 overflow-hidden bg-white">
            <img
                src="{{ media_url('home_about_image', asset('images/home-about.jpg')) }}"
                alt="{{ media_alt('home_about_image', 'About Glamhouse image') }}"
                class="w-full h-[420px] object-cover"
            >
        </div>

        <div>
            <h2 class="font-serif text-3xl">Tailored beauty with intention</h2>
            <p class="mt-4 text-black/70 leading-relaxed">
                Sibonginkosi Dube specializes in soft glam, natural glam, full glam, and picture-perfect makeup
                designed to suit individual facial features, lighting conditions, and the purpose of the look.
            </p>
            <p class="mt-4 text-black/70 leading-relaxed">
                Every session is warm, professional, and reassuring—focused on helping clients feel seen,
                comfortable, elegant, and confident.
            </p>

            <a href="{{ route('about') }}"
               class="mt-6 inline-flex rounded-full border border-black/20 px-6 py-3 hover:border-rosegold-600 hover:text-rosegold-600 transition">
                Learn More
            </a>
        </div>
    </section>

    {{-- PACKAGES --}}
    <section>
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="font-serif text-3xl">Packages</h2>
                <p class="mt-2 text-black/70">
                    Premium, tailored makeup services for events, creatives, and corporate media.
                </p>
            </div>
            <a href="{{ route('services') }}"
               class="hidden md:inline-flex rounded-full border border-black/20 px-5 py-2 hover:border-rosegold-600 hover:text-rosegold-600 transition">
                View All
            </a>
        </div>

        @if($services->count())
            <div class="mt-6 grid gap-4 md:grid-cols-2">
                @foreach($services as $service)
                    <div class="rounded-2xl border border-black/10 bg-white p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="font-semibold text-lg">{{ $service->name }}</div>
                                <div class="mt-2 text-sm text-black/70">
                                    {{ $service->description }}
                                </div>
                            </div>

                            <div class="text-right font-semibold whitespace-nowrap">
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
        @endif
    </section>

    {{-- PORTFOLIO PREVIEW --}}
    <section>
        <h2 class="font-serif text-3xl">Recent Looks</h2>
        <p class="mt-2 text-black/70">
            A glimpse of soft glam, natural glam, full glam, and camera-ready beauty.
        </p>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 md:grid-cols-4">
            @forelse($featuredPortfolio as $item)
                <div class="rounded-2xl border border-black/10 overflow-hidden bg-white">
                    <img
                        src="{{ asset('storage/'.$item->image_path) }}"
                        alt="{{ $item->title ?? 'Portfolio image' }}"
                        class="w-full h-64 object-cover"
                    >
                    <div class="p-4">
                        <div class="font-semibold">{{ $item->title ?? 'Look' }}</div>
                        <div class="text-sm text-black/60">{{ $item->category ?? 'Portfolio' }}</div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-black/10 overflow-hidden bg-white">
                    <img src="{{ media_url('home_portfolio_1', asset('images/home-portfolio-1.jpg')) }}" alt="Portfolio preview" class="w-full h-64 object-cover">
                </div>
                <div class="rounded-2xl border border-black/10 overflow-hidden bg-white">
                    <img src="{{ media_url('home_portfolio_2', asset('images/home-portfolio-2.jpg')) }}" alt="Portfolio preview" class="w-full h-64 object-cover">
                </div>
                <div class="rounded-2xl border border-black/10 overflow-hidden bg-white">
                    <img src="{{ media_url('home_portfolio_3', asset('images/home-portfolio-3.jpg')) }}" alt="Portfolio preview" class="w-full h-64 object-cover">
                </div>
                <div class="rounded-2xl border border-black/10 overflow-hidden bg-white">
                    <img src="{{ media_url('home_portfolio_4', asset('images/home-portfolio-4.jpg')) }}" alt="Portfolio preview" class="w-full h-64 object-cover">
                </div>
            @endforelse
        </div>
    </section>

    {{-- TESTIMONIAL PREVIEW --}}
    <section>
        <h2 class="font-serif text-3xl">Client Experience</h2>
        <div class="mt-6 grid gap-4 md:grid-cols-3">
            @forelse($testimonials as $testimonial)
                <div class="rounded-2xl border border-black/10 bg-white p-6">
                    <p class="text-black/70 leading-relaxed">“{{ $testimonial->content }}”</p>
                    <div class="mt-4 font-semibold">{{ $testimonial->client_name }}</div>
                </div>
            @empty
                <div class="rounded-2xl border border-black/10 bg-white p-6">
                    <p class="text-black/70">“Beautiful work, calm experience, and my makeup lasted perfectly.”</p>
                    <div class="mt-4 font-semibold">Client Testimonial</div>
                </div>
                <div class="rounded-2xl border border-black/10 bg-white p-6">
                    <p class="text-black/70">“The look felt natural, elegant, and perfect for camera.”</p>
                    <div class="mt-4 font-semibold">Client Testimonial</div>
                </div>
                <div class="rounded-2xl border border-black/10 bg-white p-6">
                    <p class="text-black/70">“Professional, warm, and detail-oriented from start to finish.”</p>
                    <div class="mt-4 font-semibold">Client Testimonial</div>
                </div>
            @endforelse
        </div>
    </section>

    {{-- CTA BANNER --}}
    <section class="rounded-3xl border border-black/10 overflow-hidden bg-white">
        <div class="grid md:grid-cols-2 items-center">
            <div class="p-8 md:p-10">
                <h2 class="font-serif text-3xl">Ready for your next look?</h2>
                <p class="mt-4 text-black/70 leading-relaxed">
                    Book a glam session tailored to your face, your event, and the way you want to be seen.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('booking.create') }}"
                       class="rounded-full bg-black px-6 py-3 text-white hover:bg-rosegold-600 transition">
                        Book Appointment
                    </a>
                    <a href="{{ route('contact') }}"
                       class="rounded-full border border-black/20 px-6 py-3 hover:border-rosegold-600 hover:text-rosegold-600 transition">
                        Contact
                    </a>
                </div>
            </div>

            <div class="h-full">
                <img
                    src="{{ media_url('home_cta_image', asset('images/home-cta.jpg')) }}"
                    alt="{{ media_alt('home_cta_image', 'Booking call to action image') }}"
                    class="w-full h-[320px] md:h-full object-cover"
                >
            </div>
        </div>
    </section>

</div>
@endsection