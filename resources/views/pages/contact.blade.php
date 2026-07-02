@extends('layouts.public')
@section('title', 'Contact - Glamhouse')

@section('content')
@php
    $contact = \App\Support\PublicBusinessContact::details();
    $socialLinks = social_links();
@endphp

<div class="mx-auto max-w-7xl space-y-10 px-4 pb-20 pt-10 sm:px-6 lg:px-8">
    <section class="grid gap-8 lg:grid-cols-[1.06fr_0.94fr]">
        <div class="space-y-5 reveal-up">
            <article class="relative overflow-hidden rounded-[2rem] border border-rosegold-200 bg-[linear-gradient(130deg,#fff9f8_0%,#fff2ef_48%,#fffefd_100%)] p-7 sm:p-8">
                <div class="absolute -right-10 top-0 h-44 w-44 rounded-full bg-rosegold-100/70 blur-2xl"></div>

                <p class="relative inline-flex rounded-full border border-rosegold-200 bg-white/75 px-3 py-1 text-[0.67rem] font-semibold uppercase tracking-[0.2em] text-rosegold-800">
                    Fast Response Contact
                </p>
                <h1 class="font-display relative mt-5 text-5xl leading-[0.95] text-[#2a1c19] sm:text-6xl">
                    {{ $contact['locality'] !== '' ? 'Plan Your '.$contact['locality'].' Glam' : 'Plan Your Glam' }}
                </h1>
                <p class="relative mt-4 max-w-xl text-base leading-relaxed text-black/70">
                    Ready to book from Google? Send the full appointment details through the form, or WhatsApp {{ $contact['business_name'] }} for quick availability before you decide.
                </p>

                <div class="relative mt-6 grid gap-3 sm:grid-cols-2">
                    <a href="{{ route('booking.create') }}" class="btn-primary inline-flex items-center justify-center text-center">
                        Book Appointment
                    </a>
                    @if($contact['has_phone'])
                        <a
                            href="{{ $contact['whatsapp_url'] }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center justify-center rounded-full border border-green-200 bg-green-50 px-5 py-3 text-center text-sm font-bold text-green-800 transition hover:-translate-y-0.5 hover:bg-green-100"
                        >
                            WhatsApp Availability
                        </a>
                    @endif
                </div>

                <div class="relative mt-5 rounded-2xl border border-black/10 bg-white/85 p-4">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-[0.16em] text-black/55">{{ $contact['location_label'] }}</div>
                            @if($contact['location_display'] !== '')
                                <div class="mt-1 text-lg font-bold text-[#2a1c19]">{{ $contact['location_display'] }}</div>
                            @endif
                            <p class="mt-1 text-sm leading-relaxed text-black/65">{{ $contact['service_mode'] }} appointments are available by arrangement. Exact arrival details are confirmed with your booking.</p>
                        </div>
                        @if($contact['maps_url'] !== '')
                            <a href="{{ $contact['maps_url'] }}" target="_blank" rel="noopener noreferrer" class="btn-outline hidden shrink-0 items-center justify-center text-sm sm:inline-flex">Open Maps</a>
                        @endif
                    </div>
                </div>

                <div class="relative mt-4 hidden gap-3 sm:grid sm:grid-cols-2">
                    @if($contact['has_phone'])
                        <a
                            href="{{ $contact['whatsapp_url'] }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="group rounded-2xl border border-black/10 bg-white/90 p-4 transition hover:-translate-y-0.5 hover:border-green-300 hover:shadow-lg"
                        >
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-green-100 text-green-700">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path d="M20.5 12a8.5 8.5 0 0 1-12.45 7.53L3 21l1.55-4.83A8.5 8.5 0 1 1 20.5 12Z"/>
                                        <path d="M9.4 8.9c.1-.2.2-.2.4-.2h.8c.1 0 .3.1.3.2l.7 1.8a.4.4 0 0 1-.1.4l-.6.7a6.4 6.4 0 0 0 2.9 2.9l.7-.6c.1-.1.3-.2.4-.1l1.8.7c.1 0 .2.2.2.3v.8c0 .2-.1.3-.2.4-.4.3-1 .5-1.5.4-1.4-.2-3-1.1-4.4-2.5-1.4-1.4-2.3-3-2.5-4.4 0-.5.1-1.1.4-1.5Z"/>
                                    </svg>
                                </span>
                                <div>
                                    <div class="text-xs font-semibold uppercase tracking-[0.14em] text-black/55">WhatsApp</div>
                                    <div class="text-sm font-bold text-[#2a1c19]">{{ $contact['phone_display'] }}</div>
                                </div>
                            </div>
                        </a>
                    @endif

                    @if($contact['has_email'])
                        <a
                            href="{{ $contact['email_url'] }}"
                            class="group rounded-2xl border border-black/10 bg-white/90 p-4 transition hover:-translate-y-0.5 hover:border-sky-300 hover:shadow-lg"
                        >
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-sky-100 text-sky-700">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <rect x="3" y="5" width="18" height="14" rx="2"/>
                                        <path d="m4 7 8 6 8-6"/>
                                    </svg>
                                </span>
                                <div>
                                    <div class="text-xs font-semibold uppercase tracking-[0.14em] text-black/55">Email</div>
                                    <div class="text-sm font-bold text-[#2a1c19]">{{ $contact['email'] }}</div>
                                </div>
                            </div>
                        </a>
                    @endif
                </div>

                <p class="relative mt-4 text-xs text-black/55">Typical response window: same day during business hours.</p>
            </article>

            <article class="glass-card reveal-up delay-1 rounded-3xl p-6">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="font-display text-3xl text-[#2a1c19]">Social Channels</h2>
                    <span class="rounded-full bg-rosegold-100 px-3 py-1 text-[0.67rem] font-semibold uppercase tracking-[0.16em] text-rosegold-800">Follow</span>
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-3">
                    @forelse($socialLinks as $link)
                        <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" class="group rounded-2xl border border-black/10 bg-white p-4 transition hover:-translate-y-0.5 hover:border-rosegold-300 hover:shadow-lg">
                            <div class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-rosegold-100 text-sm font-extrabold text-rosegold-800">
                                {{ strtoupper(substr($link['label'], 0, 1)) }}
                            </div>
                            <div class="mt-3 text-sm font-semibold">{{ $link['label'] }}</div>
                            <div class="text-xs text-black/60">Open {{ $link['label'] }}</div>
                        </a>
                    @empty
                        <div class="rounded-2xl border border-dashed border-black/15 bg-white/70 p-4 text-sm text-black/60 sm:col-span-3">
                            Social links are not published yet. Add active links in admin when the real channels are ready.
                        </div>
                    @endforelse
                </div>
            </article>

            <div class="grid gap-4 sm:grid-cols-3 reveal-up delay-2">
                <article class="rounded-3xl border border-black/10 bg-white/85 p-5">
                    <div class="text-xs font-semibold uppercase tracking-[0.15em] text-black/55">Location</div>
                    @if($contact['location_display'] !== '')
                        <div class="mt-2 text-lg font-bold text-[#2a1c19]">{{ $contact['location_display'] }}</div>
                    @endif
                    <p class="mt-2 text-sm text-black/65">{{ $contact['service_mode'] }} sessions by arrangement.</p>
                </article>

                <article class="rounded-3xl border border-black/10 bg-white/85 p-5">
                    <div class="text-xs font-semibold uppercase tracking-[0.15em] text-black/55">Need Full Booking?</div>
                    <a href="{{ route('booking.create') }}" class="btn-primary mt-3 inline-flex text-sm">Open Booking Form</a>
                    <p class="mt-2 text-sm text-black/65">Share your event details and preferred date in one go.</p>
                </article>

                @if($contact['has_phone'])
                    <article class="rounded-3xl border border-black/10 bg-white/85 p-5">
                        <div class="text-xs font-semibold uppercase tracking-[0.15em] text-black/55">Quick Check</div>
                        <a href="{{ $contact['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex rounded-full border border-green-200 bg-green-50 px-4 py-2.5 text-sm font-bold text-green-800 transition hover:bg-green-100">WhatsApp First</a>
                        <p class="mt-2 text-sm text-black/65">Ask about availability before completing the full form.</p>
                    </article>
                @endif
            </div>
        </div>

        <div class="space-y-4 reveal-up delay-1">
            <article class="glass-card overflow-hidden rounded-[1.8rem] p-3">
                <img
                    src="{{ media_url('contact_hero', asset('images/contact-hero.jpg')) }}"
                    alt="{{ media_alt('contact_hero', 'Contact hero image') }}"
                    class="h-[410px] w-full rounded-[1.35rem] object-cover"
                >
            </article>

            <div class="grid gap-4 sm:grid-cols-2">
                <article class="glass-card overflow-hidden rounded-3xl p-2">
                    <img
                        src="{{ media_url('contact_1', asset('images/contact-1.jpg')) }}"
                        alt="{{ media_alt('contact_1', 'Contact gallery image one') }}"
                        class="h-[205px] w-full rounded-2xl object-cover"
                    >
                </article>

                <article class="glass-card overflow-hidden rounded-3xl p-2">
                    <img
                        src="{{ media_url('contact_2', asset('images/contact-2.jpg')) }}"
                        alt="{{ media_alt('contact_2', 'Contact gallery image two') }}"
                        class="h-[205px] w-full rounded-2xl object-cover"
                    >
                </article>
            </div>
        </div>
    </section>
</div>
@endsection
