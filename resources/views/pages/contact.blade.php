@extends('layouts.public')
@section('title', 'Contact - Glamhouse')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-12">
    <div class="grid gap-10 md:grid-cols-2 items-start">
        <div>
            <h1 class="font-serif text-4xl">Contact</h1>
            <p class="mt-3 text-black/70">
                For bookings, corporate consultations, or general enquiries—reach out below.
            </p>

            <div class="mt-8 space-y-4">
                <div class="rounded-2xl border border-black/10 bg-white p-6">
                    <div class="font-semibold">WhatsApp / Phone</div>
                    <div class="mt-2 text-sm text-black/70">
                        <span class="text-black">Add your number here</span>
                        <div class="mt-2 text-xs text-black/60">Tip: Later we’ll turn this into a clickable WhatsApp link.</div>
                    </div>
                </div>

                <div class="rounded-2xl border border-black/10 bg-white p-6">
                    <div class="font-semibold">Email</div>
                    <div class="mt-2 text-sm text-black/70">esther2026@gmail.com</div>
                </div>

                <div class="rounded-2xl border border-black/10 bg-white p-6">
                    <div class="font-semibold">Location</div>
                    <div class="mt-2 text-sm text-black/70">Harare, Zimbabwe</div>
                </div>

                <div class="rounded-2xl border border-black/10 bg-white p-6">
                    <div class="font-semibold">Socials</div>
                    <div class="mt-2 text-sm text-black/70 space-y-2">
                        <div>Instagram: <span class="text-black">@esthers_secrets_glam</span></div>
                        <div>TikTok: <span class="text-black">@siboqueenesther</span></div>
                        <div>YouTube: <span class="text-black">Sibo_Queen_Esther</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            {{-- Cozy image stack --}}
            <div class="rounded-3xl border border-black/10 overflow-hidden bg-white">
                <img src="{{ asset('images/contact-hero.jpg') }}" alt="Contact"
                     class="w-full h-[360px] object-cover">
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-3xl border border-black/10 overflow-hidden bg-white">
                    <img src="{{ asset('images/contact-1.jpg') }}" alt="Work sample"
                         class="w-full h-[200px] object-cover">
                </div>
                <div class="rounded-3xl border border-black/10 overflow-hidden bg-white">
                    <img src="{{ asset('images/contact-2.jpg') }}" alt="Work sample"
                         class="w-full h-[200px] object-cover">
                </div>
            </div>

            <a href="{{ route('booking.create') }}"
               class="block text-center rounded-full bg-black px-6 py-3 text-white hover:bg-rosegold-600 transition">
                Go to Booking Form
            </a>
        </div>
    </div>
</div>
@endsection