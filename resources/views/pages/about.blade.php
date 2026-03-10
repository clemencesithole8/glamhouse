@extends('layouts.public')
@section('title', 'About - Glamhouse')

@section('content')
@php
    $aboutHeroMedia = media_pool(
        ['about_hero', 'home_about_image', 'home_hero', 'home_feature_1', 'contact_hero'],
        [
            asset('images/about-portrait.jpg'),
            asset('images/home-about.jpg'),
            asset('images/home-hero-fallback.jpg'),
            asset('images/home-feature-1.jpg'),
            asset('images/contact-hero.jpg'),
        ],
        'Sibonginkosi Dube portrait'
    );

    $aboutStudioMedia = media_pool(
        ['about_studio', 'home_feature_2', 'home_feature_3', 'contact_1', 'contact_2'],
        [
            asset('images/about-studio.jpg'),
            asset('images/home-feature-2.jpg'),
            asset('images/home-feature-3.jpg'),
            asset('images/contact-1.jpg'),
            asset('images/contact-2.jpg'),
        ],
        'Behind the scenes makeup session'
    );

    $aboutDetailMedia = media_pool(
        ['about_detail', 'home_feature_1', 'home_feature_2', 'home_portfolio_1', 'home_portfolio_2'],
        [
            asset('images/home-feature-1.jpg'),
            asset('images/home-feature-2.jpg'),
            asset('images/home-portfolio-1.jpg'),
            asset('images/home-portfolio-2.jpg'),
        ],
        'Makeup detail closeup'
    );
@endphp

<div class="mx-auto max-w-7xl space-y-14 px-4 pb-20 pt-10 sm:px-6 lg:px-8">
    <section class="relative overflow-hidden rounded-[2rem] border border-rosegold-200 bg-[linear-gradient(130deg,#fff9f7_0%,#fff2ee_46%,#fffefd_100%)] p-6 sm:p-8 lg:p-10 soft-reveal">
        <div class="absolute -left-20 top-8 h-52 w-52 rounded-full bg-rosegold-100/75 blur-2xl"></div>
        <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-[#f4e6c9]/70 blur-2xl"></div>

        <div class="relative grid items-center gap-8 lg:grid-cols-[1.04fr_0.96fr]">
            <div class="soft-reveal">
                <p class="inline-flex rounded-full border border-rosegold-200 bg-white/80 px-4 py-2 text-[0.68rem] font-semibold uppercase tracking-[0.24em] text-rosegold-800">
                    Meet Your Artist
                </p>
                <h1 class="font-display mt-5 text-5xl leading-[0.95] text-[#2b1c19] sm:text-6xl">About Sibonginkosi Dube</h1>
                <p class="mt-5 max-w-2xl text-base leading-relaxed text-black/70">
                    Sibonginkosi is a Harare-based professional makeup artist known for soft glam, natural glam, full glam, and camera-ready looks that still feel comfortable in real life.
                </p>
                <p class="mt-4 max-w-2xl text-base leading-relaxed text-black/70">
                    Her process blends skin prep, feature mapping, and lighting awareness so each face is enhanced with intention and precision for events, studio sessions, and executive appearances.
                </p>

                <div class="mt-8 grid max-w-xl gap-3 sm:grid-cols-3">
                    <div class="glass-card rounded-2xl p-4">
                        <div class="text-2xl font-extrabold text-[#2b1c19]"><span data-countup="120">0</span>+</div>
                        <div class="mt-1 text-[0.68rem] uppercase tracking-[0.18em] text-black/55">Client Looks</div>
                    </div>
                    <div class="glass-card rounded-2xl p-4">
                        <div class="text-2xl font-extrabold text-[#2b1c19]"><span data-countup="8">0</span>+</div>
                        <div class="mt-1 text-[0.68rem] uppercase tracking-[0.18em] text-black/55">Years Refining Craft</div>
                    </div>
                    <div class="glass-card rounded-2xl p-4">
                        <div class="text-2xl font-extrabold text-[#2b1c19]"><span data-countup="4">0</span></div>
                        <div class="mt-1 text-[0.68rem] uppercase tracking-[0.18em] text-black/55">Signature Glam Styles</div>
                    </div>
                </div>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('booking.create') }}" class="btn-primary">Book Appointment</a>
                    <a href="{{ route('services') }}" class="btn-outline">Explore Services</a>
                </div>
            </div>

            <div class="space-y-4 soft-reveal" data-reveal-delay="1">
                <article class="glass-card live-tilt image-glow float-soft overflow-hidden rounded-[1.8rem] p-3">
                    <img
                        src="{{ $aboutHeroMedia['url'] }}"
                        alt="{{ $aboutHeroMedia['alt'] }}"
                        class="h-[460px] w-full rounded-[1.3rem] object-cover"
                    >
                </article>

                <div class="grid gap-4 sm:grid-cols-2">
                    <article class="glass-card live-tilt image-glow float-soft-alt overflow-hidden rounded-3xl p-2">
                        <img
                            src="{{ $aboutStudioMedia['url'] }}"
                            alt="{{ $aboutStudioMedia['alt'] }}"
                            class="h-[190px] w-full rounded-2xl object-cover"
                        >
                    </article>
                    <div class="rounded-3xl border border-rosegold-200 bg-white/85 p-5">
                        <div class="text-xs font-semibold uppercase tracking-[0.16em] text-rosegold-700">Approach</div>
                        <p class="mt-3 text-sm leading-relaxed text-black/70">
                            Calm consultations, skin-conscious prep, and face-specific artistry for polished results that still look natural.
                        </p>
                    </div>
                </div>
                <div class="text-right text-[0.64rem] uppercase tracking-[0.18em] text-black/45">Portraits rotate from your media pool</div>
            </div>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4 soft-reveal" data-reveal-delay="1">
        <article class="glass-card rounded-3xl p-6 soft-reveal">
            <div class="text-xs font-bold uppercase tracking-[0.16em] text-rosegold-700">01</div>
            <h2 class="font-display mt-3 text-2xl text-[#2b1c19]">Facial Mapping</h2>
            <p class="mt-3 text-sm leading-relaxed text-black/65">Contour, lift, and color are placed to balance your natural structure rather than mask it.</p>
        </article>
        <article class="glass-card rounded-3xl p-6 soft-reveal" data-reveal-delay="1">
            <div class="text-xs font-bold uppercase tracking-[0.16em] text-rosegold-700">02</div>
            <h2 class="font-display mt-3 text-2xl text-[#2b1c19]">Color Precision</h2>
            <p class="mt-3 text-sm leading-relaxed text-black/65">Tone matching is adapted for your undertone, wardrobe, and lighting environment.</p>
        </article>
        <article class="glass-card rounded-3xl p-6 soft-reveal" data-reveal-delay="2">
            <div class="text-xs font-bold uppercase tracking-[0.16em] text-rosegold-700">03</div>
            <h2 class="font-display mt-3 text-2xl text-[#2b1c19]">Camera Awareness</h2>
            <p class="mt-3 text-sm leading-relaxed text-black/65">Products and finish are selected to hold up under flash, daylight, and studio setups.</p>
        </article>
        <article class="glass-card rounded-3xl p-6 soft-reveal" data-reveal-delay="3">
            <div class="text-xs font-bold uppercase tracking-[0.16em] text-rosegold-700">04</div>
            <h2 class="font-display mt-3 text-2xl text-[#2b1c19]">Long-Wear Finish</h2>
            <p class="mt-3 text-sm leading-relaxed text-black/65">Layering and setting strategy are built for all-day comfort and durability.</p>
        </article>
    </section>

    <section class="grid gap-6 lg:grid-cols-[1.08fr_0.92fr] soft-reveal" data-reveal-delay="1">
        <article class="glass-card rounded-[1.8rem] p-6 sm:p-8">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-rosegold-700">Professional Skill Highlights</p>
            <h2 class="font-display mt-2 text-4xl text-[#2b1c19] sm:text-5xl">Crafted, Not Rushed</h2>
            <p class="mt-4 max-w-3xl text-base leading-relaxed text-black/70">
                Every look is built with structured prep, precise product choices, and finish control tailored to your event goals.
            </p>

            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                <div class="rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm text-black/70">Strong understanding of facial anatomy and feature balance.</div>
                <div class="rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm text-black/70">Advanced color theory for exact skin-tone matching.</div>
                <div class="rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm text-black/70">Photography-aware application for clean camera performance.</div>
                <div class="rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm text-black/70">Skincare-first routines that protect and elevate natural skin.</div>
                <div class="rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm text-black/70">Event, studio, film, and corporate adaptability.</div>
                <div class="rounded-2xl border border-black/10 bg-white/80 px-4 py-3 text-sm text-black/70">Personalized consultations for creative and executive teams.</div>
            </div>
        </article>

        <div class="space-y-4">
            <article class="glass-card live-tilt image-glow overflow-hidden rounded-[1.8rem] p-3">
                <img
                    src="{{ $aboutDetailMedia['url'] }}"
                    alt="{{ $aboutDetailMedia['alt'] }}"
                    class="h-[360px] w-full rounded-[1.3rem] object-cover"
                >
            </article>

            <article class="rounded-3xl border border-rosegold-200 bg-[linear-gradient(130deg,#fff7f6_0%,#fff0ec_100%)] p-6">
                <div class="text-xs font-semibold uppercase tracking-[0.18em] text-rosegold-700">Client Experience</div>
                <p class="mt-3 text-sm leading-relaxed text-black/70">
                    Sessions are intentionally paced so clients feel relaxed, heard, and fully prepared before stepping into their event or shoot.
                </p>
                <a href="{{ route('contact') }}" class="mt-5 inline-flex rounded-full border border-black/20 px-4 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-black/75 transition hover:border-rosegold-500 hover:text-rosegold-800">
                    Start A Conversation
                </a>
            </article>
        </div>
    </section>

    <section class="overflow-hidden rounded-[2rem] bg-[linear-gradient(140deg,#231715_0%,#4d2f35_55%,#8f3a4e_100%)] text-white soft-reveal" data-reveal-delay="1">
        <div class="grid items-center gap-8 p-8 sm:p-10 lg:grid-cols-2 lg:p-12">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/70">Ready For Your Session</p>
                <h2 class="font-display mt-3 text-4xl leading-tight sm:text-5xl">Polished Beauty, Tailored To You</h2>
                <p class="mt-4 max-w-xl text-base leading-relaxed text-white/80">
                    Book a session for bridal, event, editorial, or executive glam and receive a look designed around your features, schedule, and camera environment.
                </p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ route('booking.create') }}" class="inline-flex rounded-full bg-white px-6 py-3 text-sm font-bold text-[#2a1c19] transition hover:bg-rosegold-100">Book Appointment</a>
                    <a href="{{ route('services') }}" class="inline-flex rounded-full border border-white/45 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">View Packages</a>
                </div>
            </div>

            <div class="rounded-3xl border border-white/20 bg-white/10 p-6 backdrop-blur-sm">
                <div class="text-xs font-semibold uppercase tracking-[0.16em] text-white/70">What You Can Expect</div>
                <div class="mt-4 grid gap-3 text-sm text-white/85">
                    <div class="rounded-2xl border border-white/20 bg-white/10 px-4 py-3">Personalized consultation before application.</div>
                    <div class="rounded-2xl border border-white/20 bg-white/10 px-4 py-3">Professional skin prep and product pairing.</div>
                    <div class="rounded-2xl border border-white/20 bg-white/10 px-4 py-3">A long-wear finish that remains elegant for hours.</div>
                    <div class="rounded-2xl border border-white/20 bg-white/10 px-4 py-3">A calm, collaborative, and confidence-first experience.</div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
