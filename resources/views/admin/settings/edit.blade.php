@extends('layouts.admin')

@section('title', 'Business Settings - Glamhouse Admin')
@section('page_title', 'Business Settings')

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
    @csrf
    @method('PATCH')

    <section class="rounded-3xl border border-black/10 bg-white p-6">
        <h2 class="font-display text-3xl text-[#2a1c19]">Business Details</h2>
        <div class="mt-5 grid gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Business Name</label>
                <input name="business_name" value="{{ old('business_name', $settings['business_name']) }}" required class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Phone</label>
                <input name="business_phone" value="{{ old('business_phone', $settings['business_phone']) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Email</label>
                <input type="email" name="business_email" value="{{ old('business_email', $settings['business_email']) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Location</label>
                <input name="business_location" value="{{ old('business_location', $settings['business_location']) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">City</label>
                <input name="business_city" value="{{ old('business_city', $settings['business_city']) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Region</label>
                <input name="business_region" value="{{ old('business_region', $settings['business_region']) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Country Code</label>
                <input name="business_country" maxlength="2" value="{{ old('business_country', $settings['business_country']) }}" class="w-full rounded-xl border-black/15 text-sm uppercase focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Country Name</label>
                <input name="business_country_name" value="{{ old('business_country_name', $settings['business_country_name']) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Street Address</label>
                <input name="business_street_address" value="{{ old('business_street_address', $settings['business_street_address']) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Postal Code</label>
                <input name="business_postal_code" value="{{ old('business_postal_code', $settings['business_postal_code']) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Outcall Travel Buffer Minutes</label>
                <input type="number" name="outcall_travel_buffer_minutes" min="0" max="720" value="{{ old('outcall_travel_buffer_minutes', $settings['outcall_travel_buffer_minutes']) }}" required class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Business Description</label>
                <textarea name="business_description" rows="3" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">{{ old('business_description', $settings['business_description']) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Google Maps URL</label>
                <input type="url" name="business_maps_url" value="{{ old('business_maps_url', $settings['business_maps_url']) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Latitude</label>
                <input name="business_latitude" value="{{ old('business_latitude', $settings['business_latitude']) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Longitude</label>
                <input name="business_longitude" value="{{ old('business_longitude', $settings['business_longitude']) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Opening Hours</label>
                <input name="business_opening_hours" value="{{ old('business_opening_hours', $settings['business_opening_hours']) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" placeholder="Mo-Sa 09:00-18:00">
            </div>
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Price Range</label>
                <input name="business_price_range" value="{{ old('business_price_range', $settings['business_price_range']) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500" placeholder="$$">
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Default WhatsApp Message</label>
                <textarea name="business_whatsapp_message" rows="3" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">{{ old('business_whatsapp_message', $settings['business_whatsapp_message']) }}</textarea>
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-black/10 bg-white p-6">
        <h2 class="font-display text-3xl text-[#2a1c19]">SEO Defaults</h2>
        <div class="mt-5 grid gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Default Title</label>
                <input name="seo_default_title" value="{{ old('seo_default_title', $settings['seo_default_title']) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Default Description</label>
                <textarea name="seo_default_description" rows="3" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">{{ old('seo_default_description', $settings['seo_default_description']) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Default Keywords</label>
                <input name="seo_default_keywords" value="{{ old('seo_default_keywords', $settings['seo_default_keywords']) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Default Image Path/URL</label>
                <input name="seo_default_image" value="{{ old('seo_default_image', $settings['seo_default_image']) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
            <div>
                <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">Robots</label>
                <input name="seo_default_robots" value="{{ old('seo_default_robots', $settings['seo_default_robots']) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-black/10 bg-white p-6">
        <h2 class="font-display text-3xl text-[#2a1c19]">Social URLs</h2>
        <div class="mt-5 grid gap-4 md:grid-cols-2">
            @foreach([
                'social_instagram_url' => 'Instagram',
                'social_facebook_url' => 'Facebook',
                'social_tiktok_url' => 'TikTok',
                'social_youtube_url' => 'YouTube',
                'social_x_url' => 'X / Twitter',
            ] as $key => $label)
                <div>
                    <label class="mb-1 block text-xs uppercase tracking-[0.13em] text-black/55">{{ $label }}</label>
                    <input type="url" name="{{ $key }}" value="{{ old($key, $settings[$key]) }}" class="w-full rounded-xl border-black/15 text-sm focus:border-rosegold-500 focus:ring-rosegold-500">
                </div>
            @endforeach
        </div>
    </section>

    <div class="flex justify-end">
        <button class="btn-primary text-sm">Save Settings</button>
    </div>
</form>
@endsection
