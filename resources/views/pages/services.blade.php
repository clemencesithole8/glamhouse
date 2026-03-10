@extends('layouts.public')
@section('title', 'Services - Glamhouse')

@section('content')
@php
    $servicesHeroMedia = media_pool(
        ['services_banner', 'home_feature_1', 'home_feature_2', 'home_feature_3', 'home_hero'],
        [
            asset('images/services-banner.jpg'),
            asset('images/home-feature-1.jpg'),
            asset('images/home-feature-2.jpg'),
            asset('images/home-feature-3.jpg'),
            asset('images/home-hero-fallback.jpg'),
        ],
        'Services showcase banner'
    );

    $servicesSupportMedia = media_pool(
        ['services_detail', 'home_about_image', 'contact_1', 'contact_2', 'home_portfolio_1'],
        [
            asset('images/home-about.jpg'),
            asset('images/contact-1.jpg'),
            asset('images/contact-2.jpg'),
            asset('images/home-portfolio-1.jpg'),
        ],
        'Professional makeup preparation image'
    );

    $pricedCount = $services->filter(static fn ($service) => !is_null($service->price))->count();
    $consultationCount = $services->where('is_consultation_based', true)->count();

    $firstService = $services->first();
    $defaultSpotlightName = $firstService?->name ?? 'No package available';
    $defaultSpotlightPrice = $firstService
        ? (!is_null($firstService->price) ? '$' . number_format((float) $firstService->price, 0) : 'Consult')
        : 'N/A';
    $defaultSpotlightType = $firstService
        ? ($firstService->is_consultation_based ? 'Consultation-based package' : 'Fixed-price package')
        : 'Please add active services from admin.';
    $defaultSpotlightDescription = $firstService?->description ?: 'Select a package to preview details here.';
@endphp

<div class="mx-auto max-w-7xl space-y-12 px-4 pb-20 pt-10 sm:px-6 lg:px-8">
    <section class="rounded-[2rem] border border-rosegold-200 bg-[linear-gradient(130deg,#fff9f8_0%,#fff3ee_48%,#fffefd_100%)] p-6 sm:p-8 lg:p-10 soft-reveal">
        <div class="grid items-center gap-8 lg:grid-cols-[1.05fr_0.95fr]">
            <div>
                <p class="inline-flex rounded-full border border-rosegold-200 bg-white/80 px-4 py-2 text-[0.68rem] font-semibold uppercase tracking-[0.24em] text-rosegold-800">
                    Signature Service Menu
                </p>
                <h1 class="font-display mt-5 text-5xl leading-[0.95] text-[#2a1c19] sm:text-6xl">Professional Packages</h1>
                <p class="mt-4 max-w-2xl text-base leading-relaxed text-black/70">
                    Skincare-first, feature-enhancing makeup services designed to hold beautifully in person and on camera from first prep to final photo.
                </p>

                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ route('booking.create') }}" class="btn-primary">Book Now</a>
                    <a href="{{ route('contact') }}" class="btn-outline">Corporate Enquiries</a>
                </div>

                <div class="mt-8 grid max-w-xl gap-3 sm:grid-cols-3">
                    <div class="glass-card rounded-2xl p-4">
                        <div class="text-2xl font-extrabold text-[#2a1c19]"><span data-countup="{{ $services->count() }}">0</span></div>
                        <div class="mt-1 text-[0.68rem] uppercase tracking-[0.18em] text-black/55">Active Packages</div>
                    </div>
                    <div class="glass-card rounded-2xl p-4">
                        <div class="text-2xl font-extrabold text-[#2a1c19]"><span data-countup="{{ $pricedCount }}">0</span></div>
                        <div class="mt-1 text-[0.68rem] uppercase tracking-[0.18em] text-black/55">Priced Services</div>
                    </div>
                    <div class="glass-card rounded-2xl p-4">
                        <div class="text-2xl font-extrabold text-[#2a1c19]"><span data-countup="{{ $consultationCount }}">0</span></div>
                        <div class="mt-1 text-[0.68rem] uppercase tracking-[0.18em] text-black/55">Consultation Led</div>
                    </div>
                </div>
            </div>

            <div class="soft-reveal" data-reveal-delay="1">
                <article class="glass-card live-tilt image-glow overflow-hidden rounded-[1.8rem] p-3">
                    <img
                        src="{{ $servicesHeroMedia['url'] }}"
                        alt="{{ $servicesHeroMedia['alt'] }}"
                        class="h-[420px] w-full rounded-[1.3rem] object-cover"
                    >
                </article>
                <div class="mt-2 text-right text-[0.64rem] uppercase tracking-[0.18em] text-black/45">Banner rotates from your active media keys</div>
            </div>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-[1.08fr_0.92fr] soft-reveal" data-reveal-delay="1" data-services-filter>
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" class="service-chip active" data-service-filter="all">All Packages</button>
                <button type="button" class="service-chip" data-service-filter="priced">Priced</button>
                <button type="button" class="service-chip" data-service-filter="consultation">Consultation</button>
                <div class="ml-auto text-[0.68rem] font-semibold uppercase tracking-[0.16em] text-black/50" data-service-count>
                    {{ $services->count() }} package{{ $services->count() === 1 ? '' : 's' }} shown
                </div>
            </div>

            <div class="mt-5 grid gap-4 md:grid-cols-2">
                @forelse($services as $service)
                    @php
                        $hasPrice = !is_null($service->price);
                        $priceLabel = $hasPrice ? '$' . number_format((float) $service->price, 0) : 'Consult';
                        $serviceType = $service->is_consultation_based ? 'consultation' : 'standard';
                        $serviceTypeLabel = $service->is_consultation_based ? 'Consultation-based package' : 'Fixed-price package';
                        $descriptionText = trim((string) ($service->description ?? 'Add package details from admin later.'));
                        $delay = ($loop->index % 3) + 1;
                    @endphp

                    <article
                        class="glass-card service-card live-tilt rounded-3xl p-6 soft-reveal"
                        data-reveal-delay="{{ $delay }}"
                        data-service-card
                        data-service-name="{{ $service->name }}"
                        data-service-price="{{ $priceLabel }}"
                        data-service-type="{{ $serviceType }}"
                        data-service-type-label="{{ $serviceTypeLabel }}"
                        data-service-price-type="{{ $hasPrice ? 'priced' : 'quote' }}"
                        data-service-description="{{ $descriptionText }}"
                        tabindex="0"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="font-display text-3xl leading-tight text-[#2a1c19]">{{ $service->name }}</div>
                                <div class="mt-2 text-sm leading-relaxed text-black/65">{{ $descriptionText }}</div>
                            </div>
                            <div class="rounded-full border border-black/15 bg-white/85 px-4 py-2 text-sm font-bold text-[#2a1c19]">{{ $priceLabel }}</div>
                        </div>

                        <div class="mt-5 flex flex-wrap gap-2">
                            <span class="rounded-full border border-black/15 bg-white/80 px-3 py-1 text-[0.68rem] font-semibold uppercase tracking-[0.14em] text-black/70">{{ $serviceTypeLabel }}</span>
                            <span class="rounded-full border border-rosegold-300/70 bg-rosegold-50 px-3 py-1 text-[0.68rem] font-semibold uppercase tracking-[0.14em] text-rosegold-800">Long-wear finish</span>
                        </div>
                    </article>
                @empty
                    <article class="rounded-3xl border border-dashed border-black/20 bg-white/65 p-6 text-sm text-black/65">
                        No active services yet. Add services in admin and they will appear here automatically.
                    </article>
                @endforelse
            </div>

            <article data-service-empty hidden class="mt-5 rounded-2xl border border-dashed border-rosegold-300 bg-rosegold-50/60 px-4 py-3 text-sm text-[#6d3b43]">
                No package matches the current filter. Try a different category.
            </article>
        </div>

        <aside class="space-y-4">
            <article class="rounded-[1.8rem] border border-rosegold-200 bg-[linear-gradient(130deg,#fff8f7_0%,#fff1ec_100%)] p-6 soft-reveal" data-reveal-delay="1">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rosegold-700">Package Spotlight</p>
                <h2 class="font-display mt-3 text-4xl leading-tight text-[#2a1c19]" data-service-name-out>{{ $defaultSpotlightName }}</h2>

                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <div class="rounded-full border border-black/15 bg-white/85 px-4 py-2 text-sm font-bold text-[#2a1c19]" data-service-price-out>{{ $defaultSpotlightPrice }}</div>
                    <div class="rounded-full border border-rosegold-300/70 bg-rosegold-50 px-3 py-1 text-[0.68rem] font-semibold uppercase tracking-[0.14em] text-rosegold-800" data-service-type-out>{{ $defaultSpotlightType }}</div>
                </div>

                <p class="mt-4 text-sm leading-relaxed text-black/70" data-service-description-out>{{ $defaultSpotlightDescription }}</p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('booking.create') }}" class="btn-primary">Book This Package</a>
                    <a href="{{ route('contact') }}" class="btn-outline">Need Custom Quote</a>
                </div>
            </article>

            <article class="glass-card live-tilt image-glow overflow-hidden rounded-[1.8rem] p-3 soft-reveal" data-reveal-delay="2">
                <img
                    src="{{ $servicesSupportMedia['url'] }}"
                    alt="{{ $servicesSupportMedia['alt'] }}"
                    class="h-[330px] w-full rounded-[1.3rem] object-cover"
                >
            </article>
        </aside>
    </section>

    <section class="rounded-[1.8rem] border border-black/10 bg-white/80 p-6 sm:p-8 soft-reveal" data-reveal-delay="1">
        <h2 class="font-display text-4xl text-[#2a1c19] sm:text-5xl">Outcall and Payment Notes</h2>
        <div class="mt-6 grid gap-4 md:grid-cols-3">
            <article class="rounded-2xl border border-black/10 bg-[#fcf8f4] p-4">
                <div class="text-xs font-semibold uppercase tracking-[0.16em] text-rosegold-700">Transport</div>
                <p class="mt-2 text-sm leading-relaxed text-black/70">Transport fees apply for outcall services and vary by location.</p>
            </article>
            <article class="rounded-2xl border border-black/10 bg-[#fcf8f4] p-4">
                <div class="text-xs font-semibold uppercase tracking-[0.16em] text-rosegold-700">Retainer</div>
                <p class="mt-2 text-sm leading-relaxed text-black/70">A deposit is required to secure your date and booking time slot.</p>
            </article>
            <article class="rounded-2xl border border-black/10 bg-[#fcf8f4] p-4">
                <div class="text-xs font-semibold uppercase tracking-[0.16em] text-rosegold-700">Balance</div>
                <p class="mt-2 text-sm leading-relaxed text-black/70">Remaining balance is due before or on the day of your appointment.</p>
            </article>
        </div>
    </section>
</div>
@endsection
