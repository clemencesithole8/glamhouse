@extends('layouts.public')
@section('title', 'Portfolio - Glamhouse')

@section('content')
@php
    $portfolioBannerMedia = media_pool(
        ['portfolio_banner', 'home_portfolio_1', 'home_portfolio_2', 'home_portfolio_3', 'home_portfolio_4'],
        [
            asset('images/portfolio-banner.jpg'),
            asset('images/home-portfolio-1.jpg'),
            asset('images/home-portfolio-2.jpg'),
            asset('images/home-portfolio-3.jpg'),
            asset('images/home-portfolio-4.jpg'),
        ],
        'Portfolio banner'
    );

    $portfolioSpotlightOne = media_pool(
        ['home_portfolio_1', 'home_portfolio_2', 'portfolio_banner', 'home_feature_1'],
        [
            asset('images/home-portfolio-1.jpg'),
            asset('images/home-portfolio-2.jpg'),
            asset('images/portfolio-banner.jpg'),
            asset('images/home-feature-1.jpg'),
        ],
        'Portfolio spotlight image'
    );

    $portfolioSpotlightTwo = media_pool(
        ['home_portfolio_2', 'home_portfolio_3', 'home_feature_2', 'home_about_image'],
        [
            asset('images/home-portfolio-2.jpg'),
            asset('images/home-portfolio-3.jpg'),
            asset('images/home-feature-2.jpg'),
            asset('images/home-about.jpg'),
        ],
        'Portfolio spotlight image'
    );

    $portfolioSpotlightThree = media_pool(
        ['home_portfolio_3', 'home_portfolio_4', 'home_feature_3', 'contact_hero'],
        [
            asset('images/home-portfolio-3.jpg'),
            asset('images/home-portfolio-4.jpg'),
            asset('images/home-feature-3.jpg'),
            asset('images/contact-hero.jpg'),
        ],
        'Portfolio spotlight image'
    );

    $portfolioPlaceholders = [
        media_pool(
            ['home_portfolio_1', 'home_portfolio_2', 'home_portfolio_3', 'home_portfolio_4'],
            [asset('images/portfolio-placeholder.jpg'), asset('images/home-portfolio-1.jpg')],
            'Portfolio placeholder'
        ),
        media_pool(
            ['home_portfolio_2', 'home_portfolio_3', 'home_portfolio_4', 'home_portfolio_1'],
            [asset('images/portfolio-placeholder.jpg'), asset('images/home-portfolio-2.jpg')],
            'Portfolio placeholder'
        ),
        media_pool(
            ['home_portfolio_3', 'home_portfolio_4', 'home_portfolio_1', 'home_portfolio_2'],
            [asset('images/portfolio-placeholder.jpg'), asset('images/home-portfolio-3.jpg')],
            'Portfolio placeholder'
        ),
    ];
@endphp

<div class="mx-auto max-w-7xl space-y-12 px-4 pb-20 pt-10 sm:px-6 lg:px-8">
    <section class="rounded-[2rem] border border-rosegold-200 bg-[linear-gradient(130deg,#fff9f8_0%,#fff3ef_48%,#fffdfc_100%)] p-6 sm:p-8 lg:p-10 soft-reveal">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-rosegold-700">Curated Work</p>
                <h1 class="font-display mt-2 text-5xl leading-[0.95] text-[#2a1c19] sm:text-6xl">Portfolio</h1>
                <p class="mt-3 max-w-2xl text-black/70">
                    A selection of soft glam, natural glam, full glam, and media-ready looks designed to read beautifully in person and on camera.
                </p>
            </div>
            <a href="{{ route('booking.create') }}" class="btn-primary w-fit">Book a Look</a>
        </div>

        <article class="mt-8 glass-card live-tilt image-glow overflow-hidden rounded-[1.8rem] p-3">
            <img src="{{ $portfolioBannerMedia['url'] }}" alt="{{ $portfolioBannerMedia['alt'] }}" class="h-[300px] w-full rounded-[1.3rem] object-cover sm:h-[360px]">
        </article>
        <div class="mt-2 text-right text-[0.64rem] uppercase tracking-[0.18em] text-black/45">Banner rotates from your media library</div>
    </section>

    <section class="grid gap-4 sm:grid-cols-3 soft-reveal" data-reveal-delay="1">
        <article class="glass-card live-tilt image-glow overflow-hidden rounded-3xl p-2 soft-reveal" data-reveal-delay="1">
            <img src="{{ $portfolioSpotlightOne['url'] }}" alt="{{ $portfolioSpotlightOne['alt'] }}" class="h-[220px] w-full rounded-2xl object-cover">
        </article>
        <article class="glass-card live-tilt image-glow overflow-hidden rounded-3xl p-2 soft-reveal" data-reveal-delay="2">
            <img src="{{ $portfolioSpotlightTwo['url'] }}" alt="{{ $portfolioSpotlightTwo['alt'] }}" class="h-[220px] w-full rounded-2xl object-cover">
        </article>
        <article class="glass-card live-tilt image-glow overflow-hidden rounded-3xl p-2 soft-reveal" data-reveal-delay="3">
            <img src="{{ $portfolioSpotlightThree['url'] }}" alt="{{ $portfolioSpotlightThree['alt'] }}" class="h-[220px] w-full rounded-2xl object-cover">
        </article>
    </section>

    <section class="soft-reveal" data-reveal-delay="1">
        <div class="mb-6 flex items-end justify-between gap-4">
            <h2 class="font-display text-4xl text-[#2a1c19] sm:text-5xl">Recent Looks</h2>
            <div class="text-xs uppercase tracking-[0.16em] text-black/45">Live, soft and responsive gallery</div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3">
            @forelse($items as $item)
                <article class="glass-card live-tilt image-glow overflow-hidden rounded-3xl soft-reveal" data-reveal-delay="1">
                    <img
                        src="{{ $item->imageUrl('thumbnail') }}"
                        alt="{{ $item->imageAlt() }}"
                        width="{{ $item->width ?: 520 }}"
                        height="{{ $item->height ?: 720 }}"
                        loading="lazy"
                        decoding="async"
                        class="h-72 w-full object-cover"
                    >
                    <div class="p-4">
                        <div class="font-display text-2xl text-[#2a1c19]">{{ $item->title ?? 'Look' }}</div>
                        <div class="text-sm text-black/60">{{ $item->category ?? 'Category' }}</div>
                    </div>
                </article>
            @empty
                @for($i = 0; $i < 6; $i++)
                    @php
                        $placeholder = $portfolioPlaceholders[$i % count($portfolioPlaceholders)];
                    @endphp
                    <article class="glass-card live-tilt image-glow overflow-hidden rounded-3xl soft-reveal" data-reveal-delay="{{ ($i % 3) + 1 }}">
                        <img src="{{ $placeholder['url'] }}" alt="{{ $placeholder['alt'] }}" class="h-72 w-full object-cover">
                        <div class="p-4">
                            <div class="font-display text-2xl text-[#2a1c19]">Add your images</div>
                            <div class="text-sm text-black/60">Upload portfolio items in admin later</div>
                        </div>
                    </article>
                @endfor
            @endforelse
        </div>

        <div class="mt-10">
            {{ $items->links() }}
        </div>
    </section>
</div>
@endsection
