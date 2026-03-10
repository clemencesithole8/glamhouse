<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - Glamhouse')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#fbf7f3] text-[#2d1f1a] antialiased">
    <div class="min-h-screen lg:flex">
        <aside class="hidden w-72 shrink-0 border-r border-black/10 bg-white/80 backdrop-blur lg:flex lg:flex-col">
            <div class="border-b border-black/10 px-6 py-6">
                <a href="{{ route('admin.dashboard') }}" class="block">
                    <div class="font-display text-3xl leading-none text-[#2a1c19]">Glamhouse</div>
                    <div class="mt-2 text-xs font-semibold uppercase tracking-[0.2em] text-rosegold-700">Admin Console</div>
                </a>
            </div>

            <nav class="flex-1 space-y-1 px-4 py-5 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-rosegold-100 text-rosegold-900 border-rosegold-200' : 'border-transparent hover:bg-rosegold-50' }} flex items-center rounded-xl border px-4 py-2.5 font-semibold transition">
                    Dashboard
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="{{ request()->routeIs('admin.bookings.*') ? 'bg-rosegold-100 text-rosegold-900 border-rosegold-200' : 'border-transparent hover:bg-rosegold-50' }} flex items-center rounded-xl border px-4 py-2.5 font-semibold transition">
                    Bookings
                </a>
                <a href="{{ route('admin.payments.index') }}" class="{{ request()->routeIs('admin.payments.*') ? 'bg-rosegold-100 text-rosegold-900 border-rosegold-200' : 'border-transparent hover:bg-rosegold-50' }} flex items-center rounded-xl border px-4 py-2.5 font-semibold transition">
                    Payments
                </a>
                <a href="{{ route('admin.media-assets.index') }}" class="{{ request()->routeIs('admin.media-assets.*') ? 'bg-rosegold-100 text-rosegold-900 border-rosegold-200' : 'border-transparent hover:bg-rosegold-50' }} flex items-center rounded-xl border px-4 py-2.5 font-semibold transition">
                    Media Assets
                </a>
                <a href="{{ route('home') }}" class="flex items-center rounded-xl border border-transparent px-4 py-2.5 font-semibold transition hover:bg-rosegold-50">
                    View Website
                </a>
            </nav>

            <div class="border-t border-black/10 px-4 py-4">
                <div class="rounded-2xl border border-black/10 bg-white px-4 py-3">
                    <div class="text-sm font-semibold">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-black/55">{{ auth()->user()->email }}</div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button class="w-full rounded-lg border border-black/15 px-3 py-2 text-xs font-semibold transition hover:bg-black hover:text-white">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex-1">
            <header class="sticky top-0 z-30 border-b border-black/10 bg-white/85 backdrop-blur">
                <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-3 sm:px-6 lg:px-8">
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-[0.18em] text-rosegold-700">Admin</div>
                        <div class="font-display text-2xl leading-none text-[#2a1c19]">@yield('page_title', 'Dashboard')</div>
                    </div>

                    <details class="relative lg:hidden">
                        <summary class="flex h-10 w-10 list-none cursor-pointer items-center justify-center rounded-full border border-black/15 bg-white text-[#2d1f1a]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M4 7h16M4 12h16M4 17h16" />
                            </svg>
                        </summary>
                        <div class="absolute right-0 mt-3 w-56 rounded-2xl border border-black/10 bg-white p-2 shadow-xl">
                            <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-rosegold-50">Dashboard</a>
                            <a href="{{ route('admin.bookings.index') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-rosegold-50">Bookings</a>
                            <a href="{{ route('admin.payments.index') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-rosegold-50">Payments</a>
                            <a href="{{ route('admin.media-assets.index') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-rosegold-50">Media Assets</a>
                            <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-rosegold-50">View Website</a>
                        </div>
                    </details>

                    <div class="hidden items-center gap-2 lg:flex">
                        @yield('page_actions')
                    </div>
                </div>
            </header>

            <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-900">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900">
                        <div class="font-semibold">Please review the form errors.</div>
                        <ul class="mt-2 list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
