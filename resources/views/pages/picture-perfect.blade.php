@extends('layouts.public')
@section('title', 'Picture Perfect - Glamhouse')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-12">
    <div class="grid gap-10 md:grid-cols-2 items-start">
        <div>
            <h1 class="font-serif text-4xl">Picture Perfect Package</h1>
            <p class="mt-4 text-black/70 leading-relaxed">
                Consultation-based, camera-ready makeup designed for professional environments where lighting,
                lenses, and long production hours demand precision.
            </p>

            <div class="mt-6 grid gap-3 text-sm text-black/70">
                <div class="rounded-2xl border border-black/10 bg-white p-4">
                    <div class="font-semibold text-black">Designed for</div>
                    <div class="mt-2">Media houses • Marketing agencies • Fashion shows • TV & news • Commercials & film • Corporate branding shoots</div>
                </div>
                <div class="rounded-2xl border border-black/10 bg-white p-4">
                    <div class="font-semibold text-black">What you get</div>
                    <div class="mt-2">Pre-shoot consultation • Skin-prep strategy • Lighting-aware application • Long-wear performance</div>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('booking.create') }}"
                   class="rounded-full bg-black px-6 py-3 text-white hover:bg-rosegold-600 transition">
                    Request Consultation
                </a>
                <a href="{{ route('contact') }}"
                   class="rounded-full border border-black/20 px-6 py-3 hover:border-rosegold-600 hover:text-rosegold-600 transition">
                    Contact for Corporate Work
                </a>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-3xl border border-black/10 overflow-hidden bg-white">
                <img src="{{ asset('images/picture-perfect-hero.jpg') }}"
                     alt="Picture Perfect"
                     class="w-full h-[360px] object-cover">
            </div>

            <div class="rounded-3xl border border-black/10 overflow-hidden bg-white">
                <img src="{{ asset('images/picture-perfect-set.jpg') }}"
                     alt="On set / studio"
                     class="w-full h-[320px] object-cover">
            </div>

            <div class="rounded-2xl border border-black/10 bg-rosegold-50 p-5 text-sm text-black/70">
                Tip: Use behind-the-scenes images here (studio lights, set work, corporate shoot).
            </div>
        </div>
    </div>
</div>
@endsection