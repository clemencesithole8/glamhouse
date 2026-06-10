@extends('layouts.public')

@section('title', "Esther's Secrets - Glamhouse")

@section('content')
@php
    $homeHeroSlides = [
        [
            'eyebrow' => 'Signature Bridal Glow',
            'caption' => 'Soft, dimensional skin work made for close-up moments and all-day wear.',
            'media' => media_pool(
                ['home_hero_slide_1', 'home_hero'],
                [asset('images/home-hero-fallback.jpg')],
                'Bridal glam makeup by Esther\'s Secrets Glamhouse'
            ),
        ],
        [
            'eyebrow' => 'Editorial Soft Glam',
            'caption' => 'Polished detail, balanced color, and a finish that photographs beautifully.',
            'media' => media_pool(
                ['home_hero_slide_2', 'home_feature_1'],
                [asset('images/home-feature-1.jpg')],
                'Editorial soft glam makeup look'
            ),
        ],
        [
            'eyebrow' => 'Event-Ready Finish',
            'caption' => 'Elegant makeup tailored to your features, lighting, outfit, and occasion.',
            'media' => media_pool(
                ['home_hero_slide_3', 'home_feature_2', 'home_feature_3'],
                [asset('images/home-feature-2.jpg'), asset('images/home-feature-3.jpg')],
                'Event makeup look by Esther\'s Secrets Glamhouse'
            ),
        ],
    ];

    $homeFeatureMainMedia = media_pool(
        ['home_feature_1', 'home_feature_2', 'home_feature_3', 'home_hero'],
        [
            asset('images/home-feature-1.jpg'),
            asset('images/home-feature-2.jpg'),
            asset('images/home-feature-3.jpg'),
            asset('images/home-hero-fallback.jpg'),
        ],
        'Feature image'
    );

    $homeFeatureTwoMedia = media_pool(
        ['home_feature_2', 'home_feature_3', 'home_about_image', 'contact_1'],
        [
            asset('images/home-feature-2.jpg'),
            asset('images/home-feature-3.jpg'),
            asset('images/home-about.jpg'),
            asset('images/contact-1.jpg'),
        ],
        'Feature image'
    );

    $homeFeatureThreeMedia = media_pool(
        ['home_feature_3', 'home_feature_2', 'home_about_image', 'contact_2'],
        [
            asset('images/home-feature-3.jpg'),
            asset('images/home-feature-2.jpg'),
            asset('images/home-about.jpg'),
            asset('images/contact-2.jpg'),
        ],
        'Feature image'
    );

    $homeAboutMedia = media_pool(
        ['home_about_image', 'home_hero', 'home_feature_1', 'home_feature_2'],
        [
            asset('images/home-about.jpg'),
            asset('images/home-hero-fallback.jpg'),
            asset('images/home-feature-1.jpg'),
            asset('images/home-feature-2.jpg'),
        ],
        'About Glamhouse image'
    );

    $homeCtaMedia = media_pool(
        ['home_cta_image', 'home_about_image', 'home_feature_3', 'contact_hero'],
        [
            asset('images/home-cta.jpg'),
            asset('images/home-about.jpg'),
            asset('images/home-feature-3.jpg'),
            asset('images/contact-hero.jpg'),
        ],
        'Booking call to action image'
    );

    $homePortfolioFallbackOne = media_pool(
        ['home_portfolio_1', 'home_portfolio_2', 'home_portfolio_3', 'home_portfolio_4'],
        [
            asset('images/home-portfolio-1.jpg'),
            asset('images/home-portfolio-2.jpg'),
            asset('images/home-portfolio-3.jpg'),
            asset('images/home-portfolio-4.jpg'),
        ],
        'Portfolio preview'
    );

    $homePortfolioFallbackTwo = media_pool(
        ['home_portfolio_2', 'home_portfolio_3', 'home_portfolio_4', 'home_portfolio_1'],
        [
            asset('images/home-portfolio-2.jpg'),
            asset('images/home-portfolio-3.jpg'),
            asset('images/home-portfolio-4.jpg'),
            asset('images/home-portfolio-1.jpg'),
        ],
        'Portfolio preview'
    );

    $homePortfolioFallbackThree = media_pool(
        ['home_portfolio_3', 'home_portfolio_4', 'home_portfolio_1', 'home_portfolio_2'],
        [
            asset('images/home-portfolio-3.jpg'),
            asset('images/home-portfolio-4.jpg'),
            asset('images/home-portfolio-1.jpg'),
            asset('images/home-portfolio-2.jpg'),
        ],
        'Portfolio preview'
    );

    $homePortfolioFallbackFour = media_pool(
        ['home_portfolio_4', 'home_portfolio_1', 'home_portfolio_2', 'home_portfolio_3'],
        [
            asset('images/home-portfolio-4.jpg'),
            asset('images/home-portfolio-1.jpg'),
            asset('images/home-portfolio-2.jpg'),
            asset('images/home-portfolio-3.jpg'),
        ],
        'Portfolio preview'
    );
@endphp

<div class="mx-auto max-w-7xl space-y-20 px-4 pb-20 pt-10 sm:px-6 lg:px-8">
    <section class="relative overflow-hidden rounded-[2rem] border border-rosegold-200 bg-[linear-gradient(130deg,#fff9f8_0%,#fff4ef_45%,#fffdfc_100%)] p-6 sm:p-10 lg:p-12 soft-reveal">
        <div class="absolute -left-16 top-6 h-44 w-44 rounded-full bg-rosegold-100/70 blur-2xl"></div>
        <div class="absolute -right-16 bottom-2 h-56 w-56 rounded-full bg-[#f4e6c9]/70 blur-2xl"></div>

        <div class="relative grid items-center gap-10 lg:grid-cols-[1.1fr_0.9fr]">
            <div>
                <p class="inline-flex rounded-full border border-rosegold-200 bg-white/70 px-4 py-2 text-[0.68rem] font-semibold uppercase tracking-[0.24em] text-rosegold-800">
                    Skincare-first * Camera-aware * Long-lasting glam
                </p>

                <h1 class="font-display mt-6 text-5xl leading-[0.95] text-[#2c1d1a] sm:text-6xl lg:text-7xl">
                    Esther's Secrets
                    <span class="block text-rosegold-700">Glamhouse</span>
                </h1>

                <p class="mt-6 max-w-xl text-base leading-relaxed text-black/70 sm:text-lg">
                    A precision makeup experience for brides, professionals, creatives, and event guests who want polished skin, sculpted features, and confidence that lasts from first photo to final dance.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('booking.create') }}" class="btn-primary">Book Now</a>
                    <a href="{{ route('picturePerfect') }}" class="btn-outline">Picture Perfect Package</a>
                </div>

                <div class="mt-10 grid max-w-md grid-cols-2 gap-4">
                    <div class="glass-card rounded-2xl p-4 soft-reveal" data-reveal-delay="1">
                        <div class="text-xl font-extrabold text-[#2c1d1a]">100+</div>
                        <div class="mt-1 text-xs uppercase tracking-[0.16em] text-black/55">Looks Delivered</div>
                    </div>
                    <div class="glass-card rounded-2xl p-4 soft-reveal" data-reveal-delay="2">
                        <div class="text-xl font-extrabold text-[#2c1d1a]">4</div>
                        <div class="mt-1 text-xs uppercase tracking-[0.16em] text-black/55">Glam Styles</div>
                    </div>
                </div>
            </div>

            <div class="soft-reveal" data-reveal-delay="1">
                <div
                    class="hero-slider glass-card live-tilt image-glow overflow-hidden rounded-[1.8rem] p-3"
                    data-image-slider
                    data-slider-interval="6200"
                    aria-label="Featured glam looks"
                >
                    <div class="relative h-[440px] overflow-hidden rounded-[1.35rem] sm:h-[520px] lg:h-[560px]">
                        @foreach($homeHeroSlides as $index => $slide)
                            <figure
                                class="hero-slide {{ $index === 0 ? 'is-active' : '' }}"
                                data-slider-slide
                                aria-hidden="{{ $index === 0 ? 'false' : 'true' }}"
                            >
                                <img
                                    src="{{ $slide['media']['url'] }}"
                                    alt="{{ $slide['media']['alt'] }}"
                                    class="h-full w-full object-cover"
                                >
                                <figcaption class="absolute inset-x-4 bottom-4 rounded-2xl border border-white/20 bg-black/55 p-4 text-white shadow-2xl backdrop-blur-md sm:inset-x-5 sm:bottom-5">
                                    <div class="text-[0.64rem] font-bold uppercase tracking-[0.22em] text-white/70">{{ $slide['eyebrow'] }}</div>
                                    <div class="mt-1 max-w-sm text-sm leading-relaxed text-white/90">{{ $slide['caption'] }}</div>
                                </figcaption>
                            </figure>
                        @endforeach

                        <button
                            type="button"
                            class="hero-slider-control left-3"
                            data-slider-prev
                            aria-label="Previous featured image"
                        >
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                <path d="m15 18-6-6 6-6" />
                            </svg>
                        </button>
                        <button
                            type="button"
                            class="hero-slider-control right-3"
                            data-slider-next
                            aria-label="Next featured image"
                        >
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                        </button>
                    </div>

                    <div class="mt-3 flex items-center justify-between gap-4 px-1">
                        <div class="flex gap-2" role="tablist" aria-label="Featured image slides">
                            @foreach($homeHeroSlides as $index => $slide)
                                <button
                                    type="button"
                                    class="hero-slider-dot {{ $index === 0 ? 'is-active' : '' }}"
                                    data-slider-dot="{{ $index }}"
                                    role="tab"
                                    aria-label="Show slide {{ $index + 1 }}"
                                    aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                                ></button>
                            @endforeach
                        </div>
                        <div class="hidden min-w-32 overflow-hidden rounded-full bg-black/10 sm:block">
                            <div class="hero-slider-progress" data-slider-progress></div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div class="glass-card rounded-2xl px-4 py-3 text-sm font-semibold">Soft glam mastery</div>
                    <div class="glass-card rounded-2xl px-4 py-3 text-sm font-semibold">Studio-light ready</div>
                </div>
                <div class="mt-2 text-right text-[0.64rem] uppercase tracking-[0.18em] text-black/45">Live visual rotation</div>
            </div>
        </div>
    </section>

    <section class="soft-reveal" data-reveal-delay="1">
        <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-rosegold-700">Why Clients Return</p>
                <h2 class="font-display mt-2 text-4xl text-[#2c1d1a] sm:text-5xl">The Signature Approach</h2>
            </div>
            <a href="{{ route('about') }}" class="btn-outline hidden md:inline-flex">Meet the Artist</a>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="glass-card soft-reveal rounded-3xl p-6" data-reveal-delay="1">
                <div class="text-xs font-bold uppercase tracking-[0.18em] text-rosegold-700">01</div>
                <h3 class="font-display mt-3 text-2xl text-[#2c1d1a]">Skin Health First</h3>
                <p class="mt-3 text-sm leading-relaxed text-black/65">Prep and product choices are tailored to skin condition, climate, and wear time so beauty lasts comfortably.</p>
            </div>
            <div class="glass-card soft-reveal rounded-3xl p-6" data-reveal-delay="2">
                <div class="text-xs font-bold uppercase tracking-[0.18em] text-rosegold-700">02</div>
                <h3 class="font-display mt-3 text-2xl text-[#2c1d1a]">Feature Mapping</h3>
                <p class="mt-3 text-sm leading-relaxed text-black/65">Every contour, highlight, and color placement is designed around your bone structure and natural expression.</p>
            </div>
            <div class="glass-card soft-reveal rounded-3xl p-6" data-reveal-delay="3">
                <div class="text-xs font-bold uppercase tracking-[0.18em] text-rosegold-700">03</div>
                <h3 class="font-display mt-3 text-2xl text-[#2c1d1a]">Camera Intelligence</h3>
                <p class="mt-3 text-sm leading-relaxed text-black/65">Looks are tested mentally against flash, daylight, and studio setups to avoid washout and texture distortion.</p>
            </div>
            <div class="glass-card soft-reveal rounded-3xl p-6" data-reveal-delay="3">
                <div class="text-xs font-bold uppercase tracking-[0.18em] text-rosegold-700">04</div>
                <h3 class="font-display mt-3 text-2xl text-[#2c1d1a]">Wear-Through Finish</h3>
                <p class="mt-3 text-sm leading-relaxed text-black/65">From vows to after-party, your glam holds its tone, shape, and softness with minimal touchups.</p>
            </div>
        </div>
    </section>

    <section class="grid gap-4 lg:grid-cols-[1.25fr_0.75fr] soft-reveal" data-reveal-delay="1">
        <div class="glass-card live-tilt image-glow group relative overflow-hidden rounded-[1.8rem] p-3">
            <img
                src="{{ $homeFeatureMainMedia['url'] }}"
                alt="{{ $homeFeatureMainMedia['alt'] }}"
                class="h-[460px] w-full rounded-[1.35rem] object-cover transition duration-500 group-hover:scale-[1.03]"
            >
            <div class="absolute inset-x-8 bottom-8 rounded-2xl bg-black/60 px-4 py-3 text-white backdrop-blur-sm">
                <div class="text-xs uppercase tracking-[0.2em] text-white/70">Editorial Soft Glam</div>
                <div class="mt-1 text-sm">A polished finish that still feels like you, only elevated.</div>
            </div>
        </div>

        <div class="grid gap-4">
            <div class="glass-card live-tilt image-glow overflow-hidden rounded-3xl p-3 soft-reveal" data-reveal-delay="2">
                <img
                    src="{{ $homeFeatureTwoMedia['url'] }}"
                    alt="{{ $homeFeatureTwoMedia['alt'] }}"
                    class="h-[220px] w-full rounded-2xl object-cover"
                >
            </div>
            <div class="glass-card live-tilt image-glow overflow-hidden rounded-3xl p-3 soft-reveal" data-reveal-delay="3">
                <img
                    src="{{ $homeFeatureThreeMedia['url'] }}"
                    alt="{{ $homeFeatureThreeMedia['alt'] }}"
                    class="h-[220px] w-full rounded-2xl object-cover"
                >
            </div>
        </div>
    </section>

    <section class="grid items-center gap-10 lg:grid-cols-2 soft-reveal" data-reveal-delay="1">
        <div class="glass-card live-tilt image-glow overflow-hidden rounded-[1.8rem] p-3">
            <img
                src="{{ $homeAboutMedia['url'] }}"
                alt="{{ $homeAboutMedia['alt'] }}"
                class="h-[460px] w-full rounded-[1.35rem] object-cover"
            >
        </div>

        <div class="soft-reveal" data-reveal-delay="2">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-rosegold-700">Artist Philosophy</p>
            <h2 class="font-display mt-3 text-4xl text-[#2c1d1a] sm:text-5xl">Tailored Beauty With Intention</h2>
            <p class="mt-5 text-base leading-relaxed text-black/70">
                Sibonginkosi Dube blends soft glam, natural glam, full glam, and picture-perfect makeup into looks that suit real faces, real lighting, and real moments.
            </p>
            <p class="mt-4 text-base leading-relaxed text-black/70">
                Every appointment is calm, collaborative, and expertly paced so you leave feeling composed, elegant, and unmistakably yourself.
            </p>

            <div class="mt-8 grid gap-3 sm:grid-cols-2">
                <div class="rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm font-semibold">Personalized consultation</div>
                <div class="rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm font-semibold">Lighting-aware product selection</div>
                <div class="rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm font-semibold">Event and studio adaptability</div>
                <div class="rounded-2xl border border-black/10 bg-white/70 px-4 py-3 text-sm font-semibold">Comfort-first finishing techniques</div>
            </div>

            <a href="{{ route('about') }}" class="btn-outline mt-8 inline-flex">Learn More</a>
        </div>
    </section>

    <section class="soft-reveal" data-reveal-delay="1">
        <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-rosegold-700">Services</p>
                <h2 class="font-display mt-2 text-4xl text-[#2c1d1a] sm:text-5xl">Packages</h2>
                <p class="mt-3 max-w-2xl text-base text-black/65">
                    Premium, tailored makeup services for private clients, events, and brand-facing work.
                </p>
            </div>
            <a href="{{ route('services') }}" class="btn-outline hidden md:inline-flex">View All</a>
        </div>

        @if($services->count())
            <div class="grid gap-4 lg:grid-cols-2">
                @foreach($services as $service)
                    <article class="glass-card live-tilt soft-reveal rounded-3xl p-6" data-reveal-delay="1">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-[0.2em] text-rosegold-700">Package</div>
                                <h3 class="font-display mt-2 text-3xl text-[#2c1d1a]">{{ $service->name }}</h3>
                                <p class="mt-3 text-sm leading-relaxed text-black/65">{{ $service->description }}</p>
                            </div>

                            <div class="rounded-2xl bg-rosegold-100 px-4 py-3 text-right text-sm font-bold text-rosegold-800">
                                @if($service->price)
                                    ${{ number_format($service->price, 0) }}
                                @else
                                    Consult
                                @endif
                            </div>
                        </div>

                        <a href="{{ route('booking.create') }}" class="btn-outline mt-6 inline-flex text-sm">Book This Look</a>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <section class="soft-reveal" data-reveal-delay="1">
        <div class="mb-8">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-rosegold-700">Portfolio</p>
            <h2 class="font-display mt-2 text-4xl text-[#2c1d1a] sm:text-5xl">Recent Looks</h2>
            <p class="mt-3 text-base text-black/65">A curated glimpse of soft glam, natural glam, and full glam transformations.</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($featuredPortfolio as $item)
                <article class="glass-card live-tilt image-glow group overflow-hidden rounded-3xl soft-reveal" data-reveal-delay="1">
                    <img
                        src="{{ asset('storage/'.$item->image_path) }}"
                        alt="{{ $item->title ?? 'Portfolio image' }}"
                        class="h-72 w-full object-cover transition duration-500 group-hover:scale-105"
                    >
                    <div class="p-4">
                        <div class="font-display text-2xl text-[#2c1d1a]">{{ $item->title ?? 'Look' }}</div>
                        <div class="text-sm text-black/60">{{ $item->category ?? 'Portfolio' }}</div>
                    </div>
                </article>
            @empty
                <div class="glass-card live-tilt image-glow overflow-hidden rounded-3xl soft-reveal" data-reveal-delay="1">
                    <img src="{{ $homePortfolioFallbackOne['url'] }}" alt="{{ $homePortfolioFallbackOne['alt'] }}" class="h-72 w-full object-cover">
                </div>
                <div class="glass-card live-tilt image-glow overflow-hidden rounded-3xl soft-reveal" data-reveal-delay="2">
                    <img src="{{ $homePortfolioFallbackTwo['url'] }}" alt="{{ $homePortfolioFallbackTwo['alt'] }}" class="h-72 w-full object-cover">
                </div>
                <div class="glass-card live-tilt image-glow overflow-hidden rounded-3xl soft-reveal" data-reveal-delay="3">
                    <img src="{{ $homePortfolioFallbackThree['url'] }}" alt="{{ $homePortfolioFallbackThree['alt'] }}" class="h-72 w-full object-cover">
                </div>
                <div class="glass-card live-tilt image-glow overflow-hidden rounded-3xl soft-reveal" data-reveal-delay="3">
                    <img src="{{ $homePortfolioFallbackFour['url'] }}" alt="{{ $homePortfolioFallbackFour['alt'] }}" class="h-72 w-full object-cover">
                </div>
            @endforelse
        </div>
    </section>

    <section class="rounded-[2rem] border border-rosegold-200 bg-[linear-gradient(130deg,#fffaf8_0%,#fff1ec_46%,#fffefd_100%)] p-6 sm:p-8 lg:p-10 soft-reveal" data-reveal-delay="1">
        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-rosegold-700">Testimonials</p>
        <h2 class="font-display mt-2 text-4xl text-[#2c1d1a] sm:text-5xl">Client Experience</h2>

        <div class="mt-7 grid gap-4 md:grid-cols-3">
            @forelse($testimonials as $testimonial)
                <article class="rounded-3xl border border-black/10 bg-white/80 p-6 backdrop-blur-sm soft-reveal" data-reveal-delay="1">
                    <p class="text-base leading-relaxed text-black/70">"{{ $testimonial->content }}"</p>
                    <div class="mt-5 text-sm font-bold uppercase tracking-[0.16em] text-[#2c1d1a]">{{ $testimonial->client_name }}</div>
                </article>
            @empty
                <article class="rounded-3xl border border-black/10 bg-white/80 p-6 backdrop-blur-sm soft-reveal" data-reveal-delay="1">
                    <p class="text-base leading-relaxed text-black/70">"Beautiful work, calm experience, and my makeup lasted perfectly."</p>
                    <div class="mt-5 text-sm font-bold uppercase tracking-[0.16em] text-[#2c1d1a]">Client Testimonial</div>
                </article>
                <article class="rounded-3xl border border-black/10 bg-white/80 p-6 backdrop-blur-sm soft-reveal" data-reveal-delay="2">
                    <p class="text-base leading-relaxed text-black/70">"The look felt natural, elegant, and perfect for camera."</p>
                    <div class="mt-5 text-sm font-bold uppercase tracking-[0.16em] text-[#2c1d1a]">Client Testimonial</div>
                </article>
                <article class="rounded-3xl border border-black/10 bg-white/80 p-6 backdrop-blur-sm soft-reveal" data-reveal-delay="3">
                    <p class="text-base leading-relaxed text-black/70">"Professional, warm, and detail-oriented from start to finish."</p>
                    <div class="mt-5 text-sm font-bold uppercase tracking-[0.16em] text-[#2c1d1a]">Client Testimonial</div>
                </article>
            @endforelse
        </div>
    </section>

    <section class="overflow-hidden rounded-[2rem] bg-[linear-gradient(140deg,#231715_0%,#54333a_58%,#8f3a4e_100%)] text-white soft-reveal" data-reveal-delay="1">
        <div class="grid items-center gap-8 md:grid-cols-2">
            <div class="p-8 sm:p-10 lg:p-12">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-white/70">Bookings Open</p>
                <h2 class="font-display mt-3 text-4xl leading-tight sm:text-5xl">Ready for your next look?</h2>
                <p class="mt-4 text-base leading-relaxed text-white/80">
                    Book a glam session tailored to your features, your event timeline, and the way you want to be photographed.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('booking.create') }}" class="inline-flex rounded-full bg-white px-6 py-3 text-sm font-bold text-[#2c1d1a] transition hover:bg-rosegold-100">Book Appointment</a>
                    <a href="{{ route('contact') }}" class="inline-flex rounded-full border border-white/45 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">Contact</a>
                </div>
            </div>

            <div class="relative h-full min-h-[320px] live-tilt image-glow">
                <img
                    src="{{ $homeCtaMedia['url'] }}"
                    alt="{{ $homeCtaMedia['alt'] }}"
                    class="h-full w-full object-cover opacity-85"
                >
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-black/20"></div>
            </div>
        </div>
    </section>
</div>
@endsection
