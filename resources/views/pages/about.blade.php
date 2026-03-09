@extends('layouts.public')
@section('title', 'About - Glamhouse')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-12">
    <div class="grid gap-10 md:grid-cols-2 items-start">
        <div>
            <h1 class="font-serif text-4xl">About Sibonginkosi Dube</h1>
            <p class="mt-4 text-black/70 leading-relaxed">
                Sibonginkosi Dube is a professional makeup artist based in Harare, Zimbabwe, specializing in soft glam,
                natural glam, full glam, and picture-perfect makeup for events, creatives, and corporate clients.
            </p>

            <p class="mt-4 text-black/70 leading-relaxed">
                Her work is rooted in a skincare-first approach, ensuring that every look not only appears flawless
                but also feels comfortable and healthy on the skin. With a strong understanding of facial anatomy,
                color theory, and photography, she tailors each look to suit the client’s natural features, skin tone,
                and the lighting conditions of the occasion.
            </p>

            <div class="mt-8 rounded-2xl border border-black/10 p-6 bg-white">
                <h2 class="font-semibold">Professional Skill Highlights</h2>
                <ul class="mt-4 grid gap-2 text-sm text-black/70 list-disc pl-5">
                    <li>Strong understanding of facial anatomy and feature balance</li>
                    <li>Advanced knowledge of color theory and skin tone matching</li>
                    <li>Photography and lighting awareness for camera-ready makeup</li>
                    <li>Skincare-focused application to protect and enhance natural skin</li>
                    <li>Looks tailored to environment (events, studio, film, corporate)</li>
                    <li>Long-lasting techniques for all-day wear</li>
                    <li>Soft glam, natural glam, full glam, and media-ready specialization</li>
                    <li>Personalized consultations for corporate and creative projects</li>
                </ul>
            </div>
        </div>

        <div class="space-y-6">
            {{-- IMAGE SLOT 1: hero portrait --}}
            <div class="rounded-3xl border border-black/10 overflow-hidden bg-white">
                <img src="{{ asset('images/about-portrait.jpg') }}"
                     alt="Makeup Artist Portrait"
                     class="w-full h-[420px] object-cover">
            </div>

            {{-- IMAGE SLOT 2: cozy studio / work shot --}}
            <div class="rounded-3xl border border-black/10 overflow-hidden bg-white">
                <img src="{{ asset('images/about-studio.jpg') }}"
                     alt="Studio / Work"
                     class="w-full h-[260px] object-cover">
            </div>

            <p class="text-sm text-black/60">
                Tip: Put your images in <code>public/images/</code> and replace filenames above
                (e.g., about-portrait.jpg).
            </p>
        </div>
    </div>
</div>
@endsection