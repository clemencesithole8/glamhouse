<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', "Esther's Secrets - Glamhouse")</title>
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
                        <div class="glass-card absolute right-0 mt-3 w-64 rounded-2xl p-3">
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

        <main class="pb-12">
            @if(session('success'))
                <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
                    <div class="glass-card rounded-2xl border border-rosegold-200 px-4 py-3 text-sm text-[#5c3437]">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="mt-16 border-t border-black/10">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <div class="font-display text-2xl text-[#2d1f1a]">Esther's Secrets</div>
                        <p class="mt-2 max-w-xs text-sm text-black/65">
                            Skincare-aware artistry built for long events, camera lights, and confident entrances.
                        </p>
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
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex flex-wrap items-center justify-between gap-3 border-t border-black/10 pt-6 text-xs text-black/55">
                    <div>&copy; {{ date('Y') }} Esther's Secrets - Glamhouse</div>
                    <div>Designed for elegant, camera-ready beauty.</div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
