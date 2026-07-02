<!doctype html>
<html lang="en">
<head>
    @php
        $routeName = request()->route()?->getName();
        $seoDefault = config('seo.default', []);
        $seoPage = config("seo.pages.{$routeName}", []);

        $metaTitleOverride = trim($__env->yieldContent('meta_title'));
        $metaTitle = $metaTitleOverride !== ''
            ? $metaTitleOverride
            : (string) ($seoPage['title'] ?? $seoDefault['title'] ?? trim($__env->yieldContent('title', config('app.name', 'Glamhouse'))));
        $metaDescription = trim($__env->yieldContent('meta_description', $seoPage['description'] ?? $seoDefault['description'] ?? ''));
        $metaKeywords = trim($__env->yieldContent('meta_keywords', $seoPage['keywords'] ?? $seoDefault['keywords'] ?? ''));
        $metaRobots = trim($__env->yieldContent('meta_robots', $seoPage['robots'] ?? $seoDefault['robots'] ?? 'index,follow'));

        $canonicalUrl = trim($__env->yieldContent('canonical_url', url()->current()));
        $defaultImagePath = $seoPage['image'] ?? $seoDefault['image'] ?? '';
        $defaultImageUrl = '';

        if (is_string($defaultImagePath) && $defaultImagePath !== '') {
            $defaultImageUrl = str_starts_with($defaultImagePath, 'http')
                ? $defaultImagePath
                : asset(ltrim($defaultImagePath, '/'));
        }

        $metaImage = trim($__env->yieldContent('meta_image', $defaultImageUrl));
        $ogType = trim($__env->yieldContent('og_type', $seoPage['type'] ?? $seoDefault['type'] ?? 'website'));

        $siteName = config('seo.site_name', config('app.name', 'Glamhouse'));
        $twitterCard = config('seo.social.twitter_card', 'summary_large_image');
        $twitterSite = config('seo.social.twitter_site', '');
        $facebookAppId = config('seo.social.facebook_app_id', '');
        $googleSiteVerification = config('seo.google.site_verification', '');

        $business = config('seo.business', []);
        $publicContact = \App\Support\PublicBusinessContact::details();
        $socialLinks = social_links();
        $address = array_filter([
            'streetAddress' => $publicContact['street_address'],
            'addressLocality' => $publicContact['locality'],
            'addressRegion' => $publicContact['region'],
            'postalCode' => $publicContact['postal_code'],
            'addressCountry' => $publicContact['country'],
        ], static fn ($value) => $value !== '');

        $schemaServices = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('services')) {
            $schemaServices = \App\Models\Service::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        $schemaTestimonials = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('testimonials')) {
            $schemaTestimonials = \App\Models\Testimonial::query()
                ->visible()
                ->whereNotNull('rating')
                ->latest()
                ->take(10)
                ->get();
        }

        $ratings = $schemaTestimonials->pluck('rating')->filter();
        $latitude = business_setting('latitude', $business['latitude'] ?? '');
        $longitude = business_setting('longitude', $business['longitude'] ?? '');
        $businessId = url('/').'#localbusiness';
        $sameAs = collect($socialLinks)->pluck('url')->filter()->values()->all();

        $localBusinessSchema = [
            '@type' => ['BeautySalon', 'ProfessionalService', 'LocalBusiness'],
            '@id' => $businessId,
            'name' => $publicContact['business_name'] ?: $siteName,
            'description' => business_setting('description', $business['description'] ?? $metaDescription),
            'url' => url('/'),
            'image' => $metaImage !== '' ? $metaImage : null,
            'telephone' => $publicContact['phone'],
            'email' => $publicContact['email'],
            'priceRange' => business_setting('price_range', $business['price_range'] ?? ''),
            'openingHours' => business_setting('opening_hours', $business['opening_hours'] ?? ''),
            'sameAs' => $sameAs !== [] ? $sameAs : null,
            'address' => $address !== [] ? array_merge(['@type' => 'PostalAddress'], $address) : null,
            'geo' => $latitude !== '' && $longitude !== '' ? [
                '@type' => 'GeoCoordinates',
                'latitude' => $latitude,
                'longitude' => $longitude,
            ] : null,
            'areaServed' => $publicContact['location_display'] !== '' ? [
                '@type' => 'Place',
                'name' => $publicContact['location_display'],
            ] : null,
            'makesOffer' => $schemaServices->map(function ($service): array {
                return array_filter([
                    '@type' => 'Offer',
                    'price' => is_null($service->price) ? null : (string) $service->price,
                    'priceCurrency' => is_null($service->price) ? null : 'USD',
                    'availability' => 'https://schema.org/InStock',
                    'itemOffered' => array_filter([
                        '@type' => 'Service',
                        'name' => $service->name,
                        'description' => $service->description,
                        'serviceType' => 'Makeup artistry',
                    ]),
                ], static fn ($value) => ! is_null($value) && $value !== '' && $value !== []);
            })->values()->all() ?: null,
            'aggregateRating' => $ratings->isNotEmpty() ? [
                '@type' => 'AggregateRating',
                'ratingValue' => round($ratings->avg(), 1),
                'reviewCount' => $ratings->count(),
                'bestRating' => 5,
                'worstRating' => 1,
            ] : null,
            'review' => $schemaTestimonials->map(function ($testimonial): array {
                return array_filter([
                    '@type' => 'Review',
                    'reviewBody' => $testimonial->content,
                    'author' => [
                        '@type' => 'Person',
                        'name' => $testimonial->client_name,
                    ],
                    'reviewRating' => $testimonial->rating ? [
                        '@type' => 'Rating',
                        'ratingValue' => $testimonial->rating,
                        'bestRating' => 5,
                        'worstRating' => 1,
                    ] : null,
                ], static fn ($value) => ! is_null($value) && $value !== '' && $value !== []);
            })->values()->all() ?: null,
        ];

        $schema = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'WebSite',
                    '@id' => url('/').'#website',
                    'url' => url('/'),
                    'name' => $siteName,
                    'publisher' => ['@id' => $businessId],
                ],
                array_filter($localBusinessSchema, static fn ($value) => ! is_null($value) && $value !== '' && $value !== []),
            ],
        ];
    @endphp

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $metaDescription }}">
    @if($metaKeywords !== '')
        <meta name="keywords" content="{{ $metaKeywords }}">
    @endif
    <meta name="robots" content="{{ $metaRobots }}">
    <meta name="theme-color" content="#fff7f3">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <meta property="og:locale" content="en_ZW">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    @if($metaImage !== '')
        <meta property="og:image" content="{{ $metaImage }}">
    @endif

    <meta name="twitter:card" content="{{ $twitterCard }}">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    @if($metaImage !== '')
        <meta name="twitter:image" content="{{ $metaImage }}">
    @endif
    @if($twitterSite !== '')
        <meta name="twitter:site" content="{{ $twitterSite }}">
    @endif
    @if($facebookAppId !== '')
        <meta property="fb:app_id" content="{{ $facebookAppId }}">
    @endif
    @if($googleSiteVerification !== '')
        <meta name="google-site-verification" content="{{ $googleSiteVerification }}">
    @endif

    <title>{{ $metaTitle }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
    @stack('structured_data')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="public-theme antialiased">
    <div class="site-shell">
        <header class="site-header sticky top-0 z-40">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-20 items-center justify-between gap-4">
                    <a href="{{ route('home') }}" class="flex flex-col leading-none">
                        <span class="font-display text-[1.7rem] tracking-wide text-[#2d1f1a]">Esther's Secrets</span>
                        <span class="text-[0.62rem] uppercase tracking-[0.32em] text-rosegold-700">Glamhouse</span>
                    </a>

                    <nav class="hidden items-center gap-6 text-sm md:flex">
                        <a href="{{ route('home') }}" class="site-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                        <a href="{{ route('about') }}" class="site-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
                        <a href="{{ route('services') }}" class="site-link {{ request()->routeIs('services') ? 'active' : '' }}">Services</a>
                        <a href="{{ route('portfolio') }}" class="site-link {{ request()->routeIs('portfolio') ? 'active' : '' }}">Portfolio</a>
                        <a href="{{ route('picturePerfect') }}" class="site-link {{ request()->routeIs('picturePerfect') ? 'active' : '' }}">Picture Perfect</a>
                        <a href="{{ route('contact') }}" class="site-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
                    </nav>

                    <div class="hidden md:block">
                        <a href="{{ route('booking.create') }}" class="btn-primary inline-flex items-center">Book Now</a>
                    </div>

                    <details class="group relative md:hidden">
                        <summary class="flex h-11 w-11 cursor-pointer list-none items-center justify-center rounded-full border border-black/15 bg-white/90 text-black">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M4 7h16M4 12h16M4 17h16" />
                            </svg>
                        </summary>
                        <div class="mobile-menu-panel glass-card absolute right-0 mt-3 w-64 rounded-2xl p-3">
                            <div class="grid gap-1 text-sm">
                                <a class="rounded-xl px-3 py-2 hover:bg-rosegold-50" href="{{ route('home') }}">Home</a>
                                <a class="rounded-xl px-3 py-2 hover:bg-rosegold-50" href="{{ route('about') }}">About</a>
                                <a class="rounded-xl px-3 py-2 hover:bg-rosegold-50" href="{{ route('services') }}">Services</a>
                                <a class="rounded-xl px-3 py-2 hover:bg-rosegold-50" href="{{ route('portfolio') }}">Portfolio</a>
                                <a class="rounded-xl px-3 py-2 hover:bg-rosegold-50" href="{{ route('picturePerfect') }}">Picture Perfect</a>
                                <a class="rounded-xl px-3 py-2 hover:bg-rosegold-50" href="{{ route('contact') }}">Contact</a>
                            </div>
                            <a href="{{ route('booking.create') }}" class="btn-primary mt-3 flex items-center justify-center text-sm">Book Now</a>
                        </div>
                    </details>
                </div>
            </div>
        </header>

        <main class="pb-28 md:pb-12">
            @if(session('success'))
                <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
                    <div class="glass-card rounded-2xl border border-rosegold-200 px-4 py-3 text-sm text-[#5c3437]">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <div class="fixed inset-x-0 bottom-0 z-50 border-t border-black/10 bg-white/95 px-3 py-3 shadow-[0_-14px_30px_rgba(36,25,21,0.12)] backdrop-blur md:hidden" style="padding-bottom: max(0.75rem, env(safe-area-inset-bottom));">
            <div class="mx-auto max-w-md">
                <div class="mb-2 flex items-center justify-between gap-3 text-[0.64rem] font-bold uppercase tracking-[0.16em] text-black/50">
                    @if($publicContact['location_display'] !== '')
                        <span>{{ $publicContact['location_display'] }}</span>
                    @endif
                    <span>{{ $publicContact['service_mode'] }}</span>
                </div>
                <div class="grid {{ $publicContact['has_phone'] ? 'grid-cols-2' : 'grid-cols-1' }} gap-2">
                    <a href="{{ route('booking.create') }}" class="btn-primary inline-flex items-center justify-center px-4 py-3 text-center text-sm">Book Now</a>
                    @if($publicContact['has_phone'])
                        <a href="{{ $publicContact['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center rounded-full border border-green-200 bg-green-50 px-4 py-3 text-center text-sm font-bold text-green-800 transition hover:bg-green-100">
                            WhatsApp
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <footer class="mt-16 border-t border-black/10 pb-24 md:pb-0">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <div class="font-display text-2xl text-[#2d1f1a]">Esther's Secrets</div>
                        <p class="mt-2 max-w-xs text-sm text-black/65">
                            Skincare-aware artistry built for long events, camera lights, and confident entrances.
                        </p>
                        @if($socialLinks !== [])
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach($socialLinks as $link)
                                    <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" class="rounded-full border border-black/10 px-3 py-1.5 text-xs font-semibold transition hover:border-rosegold-300 hover:bg-rosegold-50">
                                        {{ $link['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-[0.2em] text-black/55">Quick Links</div>
                        <div class="mt-3 grid gap-2 text-sm">
                            <a class="site-link w-max" href="{{ route('services') }}">Services</a>
                            <a class="site-link w-max" href="{{ route('portfolio') }}">Portfolio</a>
                            <a class="site-link w-max" href="{{ route('booking.create') }}">Book Appointment</a>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-[0.2em] text-black/55">Info</div>
                        <div class="mt-3 grid gap-2 text-sm">
                            <a class="site-link w-max" href="{{ route('policies') }}">Policies</a>
                            <a class="site-link w-max" href="{{ route('faq') }}">FAQ</a>
                            <a class="site-link w-max" href="{{ route('contact') }}">Contact</a>
                            <a class="site-link w-max" href="{{ route('admin.dashboard') }}">Admin Panel</a>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-[0.2em] text-black/55">Contact</div>
                        <div class="mt-3 grid gap-2 text-sm text-black/65">
                            @if($publicContact['phone_display'] !== '')
                                <a class="site-link w-max" href="{{ $publicContact['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer">{{ $publicContact['phone_display'] }}</a>
                            @endif
                            @if($publicContact['email'] !== '')
                                <a class="site-link w-max" href="{{ $publicContact['email_url'] }}">{{ $publicContact['email'] }}</a>
                            @endif
                            @if($publicContact['location_display'] !== '')
                                <span>{{ $publicContact['location_display'] }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex flex-wrap items-center justify-between gap-3 border-t border-black/10 pt-6 text-xs text-black/55">
                    <div>&copy; {{ date('Y') }} Esther's Secrets - Glamhouse</div>
                    <div class="ml-auto text-right">Designed by Clemence Wiseman - All rights reserved</div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>

