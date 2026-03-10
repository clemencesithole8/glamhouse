@extends('layouts.public')
@section('title', 'Picture Perfect - Glamhouse')

@section('content')
@php
    $heroMedia = media_pool(
        ['picture_perfect_hero', 'picture_perfect_set', 'home_feature_1', 'home_feature_2', 'home_feature_3', 'home_hero'],
        [
            asset('images/picture-perfect-hero.jpg'),
            asset('images/picture-perfect-set.jpg'),
            asset('images/home-feature-1.jpg'),
            asset('images/home-feature-2.jpg'),
            asset('images/home-feature-3.jpg'),
        ],
        'Picture Perfect hero image'
    );

    $setMediaOne = media_pool(
        ['picture_perfect_set', 'home_feature_2', 'home_feature_3', 'contact_hero', 'home_about_image'],
        [
            asset('images/picture-perfect-set.jpg'),
            asset('images/home-feature-2.jpg'),
            asset('images/home-feature-3.jpg'),
            asset('images/contact-hero.jpg'),
            asset('images/home-about.jpg'),
        ],
        'Picture Perfect on-set image'
    );

    $setMediaTwo = media_pool(
        ['home_feature_1', 'home_about_image', 'home_hero', 'contact_1', 'contact_2'],
        [
            asset('images/home-feature-1.jpg'),
            asset('images/home-about.jpg'),
            asset('images/home-hero-fallback.jpg'),
            asset('images/contact-1.jpg'),
            asset('images/contact-2.jpg'),
        ],
        'Makeup closeup image'
    );

    $ctaMedia = media_pool(
        ['picture_perfect_set', 'picture_perfect_hero', 'home_cta_image', 'contact_hero', 'home_feature_3'],
        [
            asset('images/picture-perfect-set.jpg'),
            asset('images/picture-perfect-hero.jpg'),
            asset('images/home-cta.jpg'),
            asset('images/contact-hero.jpg'),
            asset('images/home-feature-3.jpg'),
        ],
        'Picture Perfect package image'
    );
@endphp

<div class="mx-auto max-w-7xl space-y-16 px-4 pb-20 pt-10 sm:px-6 lg:px-8">
    <section class="grid gap-8 lg:grid-cols-[1.02fr_0.98fr]">
        <article class="relative overflow-hidden rounded-[2rem] border border-rosegold-200 bg-[linear-gradient(130deg,#fff9f8_0%,#fff2ef_44%,#fffefd_100%)] p-7 sm:p-9 reveal-up">
            <div class="absolute -right-12 top-3 h-48 w-48 rounded-full bg-rosegold-100/70 blur-2xl"></div>

            <p class="relative inline-flex rounded-full border border-rosegold-200 bg-white/85 px-3 py-1 text-[0.68rem] font-semibold uppercase tracking-[0.2em] text-rosegold-800">
                Signature Editorial Service
            </p>
            <h1 class="font-display relative mt-5 text-5xl leading-[0.95] text-[#2a1c19] sm:text-6xl">Picture Perfect Package</h1>
            <p class="relative mt-4 max-w-3xl text-base leading-relaxed text-black/70">
                Consultation-based, camera-ready makeup crafted for high-visibility environments where lighting, lenses, and long production hours demand precision.
            </p>

            <div class="relative mt-6 grid gap-3 sm:grid-cols-2">
                <div class="rounded-2xl border border-black/10 bg-white/85 p-4">
                    <div class="text-xs uppercase tracking-[0.14em] text-black/50">Designed For</div>
                    <div class="mt-2 text-sm text-black/75">Media houses, marketing agencies, fashion shows, TV/news, commercials, film, corporate brand shoots.</div>
                </div>
                <div class="rounded-2xl border border-black/10 bg-white/85 p-4">
                    <div class="text-xs uppercase tracking-[0.14em] text-black/50">Core Outcome</div>
                    <div class="mt-2 text-sm text-black/75">Skin that reads polished on camera and makeup that survives long schedules with minimal touchups.</div>
                </div>
            </div>

            <div class="relative mt-7 flex flex-wrap gap-3">
                <a href="{{ route('booking.create') }}" class="btn-primary">Request Consultation</a>
                <a href="{{ route('contact') }}" class="btn-outline">Corporate Enquiries</a>
            </div>
        </article>

        <div class="space-y-4 reveal-up delay-1">
            <article class="glass-card overflow-hidden rounded-[1.8rem] p-3">
                <img
                    src="{{ $heroMedia['url'] }}"
                    alt="{{ $heroMedia['alt'] }}"
                    class="h-[420px] w-full rounded-[1.3rem] object-cover"
                >
            </article>

            <div class="-mt-1 text-right text-[0.64rem] uppercase tracking-[0.2em] text-black/40">
                Visuals rotate automatically
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <article class="glass-card overflow-hidden rounded-3xl p-2">
                    <img
                        src="{{ $setMediaOne['url'] }}"
                        alt="{{ $setMediaOne['alt'] }}"
                        class="h-[220px] w-full rounded-2xl object-cover"
                    >
                </article>
                <article class="glass-card overflow-hidden rounded-3xl p-2">
                    <img
                        src="{{ $setMediaTwo['url'] }}"
                        alt="{{ $setMediaTwo['alt'] }}"
                        class="h-[220px] w-full rounded-2xl object-cover"
                    >
                </article>
            </div>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <article class="glass-card rounded-3xl p-6 reveal-up">
            <div class="text-xs font-bold uppercase tracking-[0.16em] text-rosegold-700">01</div>
            <h2 class="font-display mt-3 text-2xl text-[#2a1c19]">Pre-shoot Consultation</h2>
            <p class="mt-3 text-sm leading-relaxed text-black/65">Brief alignment on creative direction, wardrobe, moodboard, and lens/lighting profile.</p>
        </article>

        <article class="glass-card rounded-3xl p-6 reveal-up delay-1">
            <div class="text-xs font-bold uppercase tracking-[0.16em] text-rosegold-700">02</div>
            <h2 class="font-display mt-3 text-2xl text-[#2a1c19]">Skin Prep Strategy</h2>
            <p class="mt-3 text-sm leading-relaxed text-black/65">Targeted prep to balance texture, hydration, and hold before makeup application.</p>
        </article>

        <article class="glass-card rounded-3xl p-6 reveal-up delay-2">
            <div class="text-xs font-bold uppercase tracking-[0.16em] text-rosegold-700">03</div>
            <h2 class="font-display mt-3 text-2xl text-[#2a1c19]">Lighting-aware Application</h2>
            <p class="mt-3 text-sm leading-relaxed text-black/65">Tone and finish tuned for flash, studio setups, daylight, and mixed environments.</p>
        </article>

        <article class="glass-card rounded-3xl p-6 reveal-up delay-3">
            <div class="text-xs font-bold uppercase tracking-[0.16em] text-rosegold-700">04</div>
            <h2 class="font-display mt-3 text-2xl text-[#2a1c19]">Long-wear Performance</h2>
            <p class="mt-3 text-sm leading-relaxed text-black/65">Setting and balancing techniques designed for long schedules and demanding sets.</p>
        </article>
    </section>

    <section class="rounded-[2rem] border border-black/10 bg-white p-6 sm:p-8 lg:p-10">
        <div class="grid gap-8 lg:grid-cols-[1fr_0.95fr]">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-rosegold-700">Production Workflow</p>
                <h2 class="font-display mt-2 text-4xl text-[#2a1c19] sm:text-5xl">Built For Camera Days</h2>
                <p class="mt-4 max-w-2xl text-base leading-relaxed text-black/70">
                    Picture Perfect sessions are structured so beauty and schedule stay in sync from prep through capture.
                </p>

                <div class="mt-6 space-y-3">
                    <div class="rounded-2xl border border-black/10 bg-[#fbf7f3] px-4 py-3 text-sm"><span class="font-semibold">Briefing:</span> clarifying concept, shot style, and expected runtime.</div>
                    <div class="rounded-2xl border border-black/10 bg-[#fbf7f3] px-4 py-3 text-sm"><span class="font-semibold">Execution:</span> precision makeup built for close-up and wide-shot consistency.</div>
                    <div class="rounded-2xl border border-black/10 bg-[#fbf7f3] px-4 py-3 text-sm"><span class="font-semibold">Support:</span> touchup planning for scene changes and lighting transitions.</div>
                </div>
            </div>

            <article class="rounded-3xl border border-rosegold-200 bg-[linear-gradient(130deg,#fff8f7_0%,#fff0eb_100%)] p-6">
                <h3 class="font-display text-3xl text-[#2a1c19]">Ideal For</h3>
                <div class="mt-5 grid gap-3 text-sm">
                    <div class="rounded-xl border border-black/10 bg-white/80 px-4 py-3">Brand campaign shoots</div>
                    <div class="rounded-xl border border-black/10 bg-white/80 px-4 py-3">TV and broadcast appearances</div>
                    <div class="rounded-xl border border-black/10 bg-white/80 px-4 py-3">Corporate media production</div>
                    <div class="rounded-xl border border-black/10 bg-white/80 px-4 py-3">Editorial and runway environments</div>
                    <div class="rounded-xl border border-black/10 bg-white/80 px-4 py-3">Film and commercial recording days</div>
                </div>
            </article>
        </div>
    </section>

    <section class="overflow-hidden rounded-[2rem] bg-[linear-gradient(140deg,#231715_0%,#533239_58%,#8f3a4e_100%)] text-white">
        <div class="grid items-center gap-8 md:grid-cols-2">
            <div class="p-8 sm:p-10 lg:p-12">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/70">Ready To Book</p>
                <h2 class="font-display mt-3 text-4xl leading-tight sm:text-5xl">Let Your Next Shoot Read Flawlessly On Camera</h2>
                <p class="mt-4 text-base leading-relaxed text-white/80">
                    Secure a consultation and get a makeup plan tailored to your production environment.
                </p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ route('booking.create') }}" class="inline-flex rounded-full bg-white px-6 py-3 text-sm font-bold text-[#2a1c19] transition hover:bg-rosegold-100">Request Picture Perfect Booking</a>
                    <a href="{{ route('contact') }}" class="inline-flex rounded-full border border-white/45 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">Talk To Us</a>
                </div>
            </div>

            <div class="relative min-h-[320px] h-full">
                <img
                    src="{{ $ctaMedia['url'] }}"
                    alt="{{ $ctaMedia['alt'] }}"
                    class="h-full w-full object-cover opacity-85"
                >
                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-black/20"></div>
            </div>
        </div>
    </section>
</div>
@endsection
