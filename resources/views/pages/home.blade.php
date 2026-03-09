@extends('layouts.public')

@section('title', "Esther's Secrets - Glamhouse")

@section('content')
<div class="mx-auto max-w-7xl space-y-20 px-4 pb-20 pt-10 sm:px-6 lg:px-8">
    <section class="relative overflow-hidden rounded-[2rem] border border-rosegold-200 bg-[linear-gradient(130deg,#fff9f8_0%,#fff4ef_45%,#fffdfc_100%)] p-6 sm:p-10 lg:p-12">
        <div class="absolute -left-16 top-6 h-44 w-44 rounded-full bg-rosegold-100/70 blur-2xl"></div>
        <div class="absolute -right-16 bottom-2 h-56 w-56 rounded-full bg-[#f4e6c9]/70 blur-2xl"></div>

        <div class="relative grid items-center gap-10 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="reveal-up">
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
                    <div class="glass-card rounded-2xl p-4 reveal-up delay-1">
                        <div class="text-xl font-extrabold text-[#2c1d1a]">100+</div>
                        <div class="mt-1 text-xs uppercase tracking-[0.16em] text-black/55">Looks Delivered</div>
                    </div>
                    <div class="glass-card rounded-2xl p-4 reveal-up delay-2">
                        <div class="text-xl font-extrabold text-[#2c1d1a]">4</div>
                        <div class="mt-1 text-xs uppercase tracking-[0.16em] text-black/55">Glam Styles</div>
                    </div>
                </div>
            </div>

            <div class="reveal-up delay-1">
                <div class="glass-card overflow-hidden rounded-[1.8rem] p-3">
                    <img
                        src="{{ media_url('home_hero', asset('images/home-hero-fallback.jpg')) }}"
                        alt="{{ media_alt('home_hero', 'Glamhouse hero image') }}"
                        class="h-[560px] w-full rounded-[1.35rem] object-cover"
                    >
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div class="glass-card rounded-2xl px-4 py-3 text-sm font-semibold">Soft glam mastery</div>
                    <div class="glass-card rounded-2xl px-4 py-3 text-sm font-semibold">Studio-light ready</div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-rosegold-700">Why Clients Return</p>
                <h2 class="font-display mt-2 text-4xl text-[#2c1d1a] sm:text-5xl">The Signature Approach</h2>
            </div>
            <a href="{{ route('about') }}" class="btn-outline hidden md:inline-flex">Meet the Artist</a>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="glass-card reveal-up rounded-3xl p-6">
                <div class="text-xs font-bold uppercase tracking-[0.18em] text-rosegold-700">01</div>
                <h3 class="font-display mt-3 text-2xl text-[#2c1d1a]">Skin Health First</h3>
                <p class="mt-3 text-sm leading-relaxed text-black/65">Prep and product choices are tailored to skin condition, climate, and wear time so beauty lasts comfortably.</p>
            </div>
            <div class="glass-card reveal-up delay-1 rounded-3xl p-6">
                <div class="text-xs font-bold uppercase tracking-[0.18em] text-rosegold-700">02</div>
                <h3 class="font-display mt-3 text-2xl text-[#2c1d1a]">Feature Mapping</h3>
                <p class="mt-3 text-sm leading-relaxed text-black/65">Every contour, highlight, and color placement is designed around your bone structure and natural expression.</p>
            </div>
            <div class="glass-card reveal-up delay-2 rounded-3xl p-6">
                <div class="text-xs font-bold uppercase tracking-[0.18em] text-rosegold-700">03</div>
                <h3 class="font-display mt-3 text-2xl text-[#2c1d1a]">Camera Intelligence</h3>
                <p class="mt-3 text-sm leading-relaxed text-black/65">Looks are tested mentally against flash, daylight, and studio setups to avoid washout and texture distortion.</p>
            </div>
            <div class="glass-card reveal-up delay-3 rounded-3xl p-6">
                <div class="text-xs font-bold uppercase tracking-[0.18em] text-rosegold-700">04</div>
                <h3 class="font-display mt-3 text-2xl text-[#2c1d1a]">Wear-Through Finish</h3>
                <p class="mt-3 text-sm leading-relaxed text-black/65">From vows to after-party, your glam holds its tone, shape, and softness with minimal touchups.</p>
            </div>
        </div>
    </section>

    <section class="grid gap-4 lg:grid-cols-[1.25fr_0.75fr]">
        <div class="glass-card group relative overflow-hidden rounded-[1.8rem] p-3 reveal-up">
            <img
                src="{{ media_url('home_feature_1', asset('images/home-feature-1.jpg')) }}"
                alt="{{ media_alt('home_feature_1', 'Soft glam makeup image') }}"
                class="h-[460px] w-full rounded-[1.35rem] object-cover transition duration-500 group-hover:scale-[1.03]"
            >
            <div class="absolute inset-x-8 bottom-8 rounded-2xl bg-black/60 px-4 py-3 text-white backdrop-blur-sm">
                <div class="text-xs uppercase tracking-[0.2em] text-white/70">Editorial Soft Glam</div>
                <div class="mt-1 text-sm">A polished finish that still feels like you, only elevated.</div>
            </div>
        </div>

        <div class="grid gap-4">
            <div class="glass-card overflow-hidden rounded-3xl p-3 reveal-up delay-1">
                <img
                    src="{{ media_url('home_feature_2', asset('images/home-feature-2.jpg')) }}"
                    alt="{{ media_alt('home_feature_2', 'Natural glam makeup image') }}"
                    class="h-[220px] w-full rounded-2xl object-cover"
                >
            </div>
            <div class="glass-card overflow-hidden rounded-3xl p-3 reveal-up delay-2">
                <img
                    src="{{ media_url('home_feature_3', asset('images/home-feature-3.jpg')) }}"
                    alt="{{ media_alt('home_feature_3', 'Full glam makeup image') }}"
                    class="h-[220px] w-full rounded-2xl object-cover"
                >
            </div>
        </div>
    </section>

    <section class="grid items-center gap-10 lg:grid-cols-2">
        <div class="glass-card overflow-hidden rounded-[1.8rem] p-3 reveal-up">
            <img
                src="{{ media_url('home_about_image', asset('images/home-about.jpg')) }}"
                alt="{{ media_alt('home_about_image', 'About Glamhouse image') }}"
                class="h-[460px] w-full rounded-[1.35rem] object-cover"
            >
        </div>

        <div class="reveal-up delay-1">
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

    <section>
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
                    <article class="glass-card reveal-up rounded-3xl p-6">
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

    <section>
        <div class="mb-8">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-rosegold-700">Portfolio</p>
            <h2 class="font-display mt-2 text-4xl text-[#2c1d1a] sm:text-5xl">Recent Looks</h2>
            <p class="mt-3 text-base text-black/65">A curated glimpse of soft glam, natural glam, and full glam transformations.</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($featuredPortfolio as $item)
                <article class="glass-card group overflow-hidden rounded-3xl reveal-up">
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
                <div class="glass-card overflow-hidden rounded-3xl reveal-up">
                    <img src="{{ media_url('home_portfolio_1', asset('images/home-portfolio-1.jpg')) }}" alt="Portfolio preview" class="h-72 w-full object-cover">
                </div>
                <div class="glass-card overflow-hidden rounded-3xl reveal-up delay-1">
                    <img src="{{ media_url('home_portfolio_2', asset('images/home-portfolio-2.jpg')) }}" alt="Portfolio preview" class="h-72 w-full object-cover">
                </div>
                <div class="glass-card overflow-hidden rounded-3xl reveal-up delay-2">
                    <img src="{{ media_url('home_portfolio_3', asset('images/home-portfolio-3.jpg')) }}" alt="Portfolio preview" class="h-72 w-full object-cover">
                </div>
                <div class="glass-card overflow-hidden rounded-3xl reveal-up delay-3">
                    <img src="{{ media_url('home_portfolio_4', asset('images/home-portfolio-4.jpg')) }}" alt="Portfolio preview" class="h-72 w-full object-cover">
                </div>
            @endforelse
        </div>
    </section>

    <section class="rounded-[2rem] border border-rosegold-200 bg-[linear-gradient(130deg,#fffaf8_0%,#fff1ec_46%,#fffefd_100%)] p-6 sm:p-8 lg:p-10">
        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-rosegold-700">Testimonials</p>
        <h2 class="font-display mt-2 text-4xl text-[#2c1d1a] sm:text-5xl">Client Experience</h2>

        <div class="mt-7 grid gap-4 md:grid-cols-3">
            @forelse($testimonials as $testimonial)
                <article class="rounded-3xl border border-black/10 bg-white/80 p-6 backdrop-blur-sm">
                    <p class="text-base leading-relaxed text-black/70">"{{ $testimonial->content }}"</p>
                    <div class="mt-5 text-sm font-bold uppercase tracking-[0.16em] text-[#2c1d1a]">{{ $testimonial->client_name }}</div>
                </article>
            @empty
                <article class="rounded-3xl border border-black/10 bg-white/80 p-6 backdrop-blur-sm">
                    <p class="text-base leading-relaxed text-black/70">"Beautiful work, calm experience, and my makeup lasted perfectly."</p>
                    <div class="mt-5 text-sm font-bold uppercase tracking-[0.16em] text-[#2c1d1a]">Client Testimonial</div>
                </article>
                <article class="rounded-3xl border border-black/10 bg-white/80 p-6 backdrop-blur-sm">
                    <p class="text-base leading-relaxed text-black/70">"The look felt natural, elegant, and perfect for camera."</p>
                    <div class="mt-5 text-sm font-bold uppercase tracking-[0.16em] text-[#2c1d1a]">Client Testimonial</div>
                </article>
                <article class="rounded-3xl border border-black/10 bg-white/80 p-6 backdrop-blur-sm">
                    <p class="text-base leading-relaxed text-black/70">"Professional, warm, and detail-oriented from start to finish."</p>
                    <div class="mt-5 text-sm font-bold uppercase tracking-[0.16em] text-[#2c1d1a]">Client Testimonial</div>
                </article>
            @endforelse
        </div>
    </section>

    <section class="overflow-hidden rounded-[2rem] bg-[linear-gradient(140deg,#231715_0%,#54333a_58%,#8f3a4e_100%)] text-white">
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

            <div class="relative h-full min-h-[320px]">
                <img
                    src="{{ media_url('home_cta_image', asset('images/home-cta.jpg')) }}"
                    alt="{{ media_alt('home_cta_image', 'Booking call to action image') }}"
                    class="h-full w-full object-cover opacity-85"
                >
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-black/20"></div>
            </div>
        </div>
    </section>
</div>
@endsection
