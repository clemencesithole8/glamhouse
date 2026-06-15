<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public const DEFAULTS = [
        'business_phone' => '+263784721479',
        'business_email' => '',
        'business_name' => "Esther's Secrets - Glamhouse",
        'business_description' => 'Skincare-first professional makeup artistry in Harare.',
        'business_location' => 'Harare, Zimbabwe',
        'business_street_address' => '',
        'business_city' => 'Harare',
        'business_region' => 'Harare',
        'business_postal_code' => '',
        'business_country' => 'ZW',
        'business_country_name' => 'Zimbabwe',
        'business_maps_url' => '',
        'business_latitude' => '',
        'business_longitude' => '',
        'business_opening_hours' => 'Mo-Sa 09:00-18:00',
        'business_price_range' => '$$',
        'business_whatsapp_message' => 'Hi Glamhouse, I found you on Google and would like to book a makeup appointment in Harare.',
        'seo_default_title' => "Esther's Secrets - Glamhouse | Professional Makeup Artist in Harare",
        'seo_default_description' => 'Skincare-first makeup artistry in Harare for bridal, events, corporate, and camera-ready looks.',
        'seo_default_keywords' => 'makeup artist Harare, glam makeup Zimbabwe, bridal makeup Harare, professional makeup services',
        'seo_default_image' => '/images/home-hero-fallback.jpg',
        'seo_default_robots' => 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1',
        'social_instagram_url' => '',
        'social_facebook_url' => '',
        'social_tiktok_url' => '',
        'social_youtube_url' => '',
        'social_x_url' => '',
        'outcall_travel_buffer_minutes' => '0',
    ];

    public function edit()
    {
        return view('admin.settings.edit', [
            'settings' => Setting::valuesWithDefaults(self::DEFAULTS),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'business_phone' => ['nullable','string','max:50'],
            'business_email' => ['nullable','email','max:255'],
            'business_name' => ['required','string','max:255'],
            'business_description' => ['nullable','string','max:500'],
            'business_location' => ['nullable','string','max:255'],
            'business_street_address' => ['nullable','string','max:255'],
            'business_city' => ['nullable','string','max:100'],
            'business_region' => ['nullable','string','max:100'],
            'business_postal_code' => ['nullable','string','max:40'],
            'business_country' => ['nullable','string','size:2'],
            'business_country_name' => ['nullable','string','max:100'],
            'business_maps_url' => ['nullable','url','max:2048'],
            'business_latitude' => ['nullable','numeric','between:-90,90'],
            'business_longitude' => ['nullable','numeric','between:-180,180'],
            'business_opening_hours' => ['nullable','string','max:255'],
            'business_price_range' => ['nullable','string','max:20'],
            'business_whatsapp_message' => ['nullable','string','max:500'],
            'seo_default_title' => ['nullable','string','max:255'],
            'seo_default_description' => ['nullable','string','max:500'],
            'seo_default_keywords' => ['nullable','string','max:500'],
            'seo_default_image' => ['nullable','string','max:500'],
            'seo_default_robots' => ['nullable','string','max:255'],
            'social_instagram_url' => ['nullable','url','max:2048'],
            'social_facebook_url' => ['nullable','url','max:2048'],
            'social_tiktok_url' => ['nullable','url','max:2048'],
            'social_youtube_url' => ['nullable','url','max:2048'],
            'social_x_url' => ['nullable','url','max:2048'],
            'outcall_travel_buffer_minutes' => ['required','integer','min:0','max:720'],
        ]);

        $before = Setting::valuesWithDefaults(self::DEFAULTS);
        $after = array_replace(self::DEFAULTS, $data);

        Setting::setMany($after);

        AuditLog::record('settings.updated', null, $before, $after, 'Business and SEO settings updated.');

        return back()->with('success', 'Business settings updated.');
    }
}
