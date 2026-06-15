<?php

use App\Models\MediaAsset;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

if (! function_exists('media_assets_table_exists')) {
    function media_assets_table_exists(): bool
    {
        return Schema::hasTable('media_assets');
    }
}

if (! function_exists('media_url')) {
    function media_url(string $key, ?string $fallback = null): string
    {
        if (! media_assets_table_exists()) {
            return $fallback ?? '';
        }

        $asset = MediaAsset::query()
            ->where('key', $key)
            ->where('is_active', true)
            ->first();

        if ($asset) {
            return $asset->url();
        }

        // fallback can be asset('images/x.jpg') or any URL
        return $fallback ?? '';
    }
}

if (! function_exists('media_thumbnail_url')) {
    function media_thumbnail_url(string $key, ?string $fallback = null): string
    {
        if (! media_assets_table_exists()) {
            return $fallback ?? '';
        }

        $asset = MediaAsset::query()
            ->where('key', $key)
            ->where('is_active', true)
            ->first();

        return $asset ? $asset->url('thumbnail') : ($fallback ?? '');
    }
}

if (! function_exists('media_alt')) {
    function media_alt(string $key, string $default = ''): string
    {
        if (! media_assets_table_exists()) {
            return $default;
        }

        $asset = MediaAsset::query()
            ->where('key', $key)
            ->where('is_active', true)
            ->first();

        return $asset?->alt ?? $default;
    }
}

if (! function_exists('media_pool')) {
    /**
     * Pick a random active media asset from a key pool; fallback to random local image.
     *
     * @return array{key:?string,url:string,thumbnail_url:string,alt:string,width:?int,height:?int}
     */
    function media_pool(array $keys, array $fallbacks = [], string $defaultAlt = ''): array
    {
        $keys = array_values(array_filter(array_map(static fn ($key) => is_string($key) ? trim($key) : '', $keys)));
        $fallbacks = array_values(array_filter(array_map(static fn ($url) => is_string($url) ? trim($url) : '', $fallbacks)));

        if ($keys !== [] && media_assets_table_exists()) {
            $assets = MediaAsset::query()
                ->whereIn('key', $keys)
                ->where('is_active', true)
                ->get();

            if ($assets->isNotEmpty()) {
                $asset = $assets->random();

                return [
                    'key' => $asset->key,
                    'url' => $asset->url(),
                    'thumbnail_url' => $asset->url('thumbnail'),
                    'alt' => $asset->alt ?: $defaultAlt,
                    'width' => $asset->width,
                    'height' => $asset->height,
                ];
            }
        }

        if ($fallbacks !== []) {
            $fallback = $fallbacks[array_rand($fallbacks)];

            return [
                'key' => null,
                'url' => $fallback,
                'thumbnail_url' => $fallback,
                'alt' => $defaultAlt,
                'width' => null,
                'height' => null,
            ];
        }

        return [
            'key' => null,
            'url' => '',
            'thumbnail_url' => '',
            'alt' => $defaultAlt,
            'width' => null,
            'height' => null,
        ];
    }
}

if (! function_exists('setting_value')) {
    function setting_value(string $key, ?string $default = null): ?string
    {
        return Setting::valueFor($key, $default);
    }
}

if (! function_exists('business_setting')) {
    function business_setting(string $key, ?string $default = null): ?string
    {
        $settingKeys = [
            'name' => 'business_name',
            'description' => 'business_description',
            'phone' => 'business_phone',
            'email' => 'business_email',
            'street_address' => 'business_street_address',
            'locality' => 'business_city',
            'region' => 'business_region',
            'postal_code' => 'business_postal_code',
            'country' => 'business_country',
            'country_name' => 'business_country_name',
            'maps_url' => 'business_maps_url',
            'latitude' => 'business_latitude',
            'longitude' => 'business_longitude',
            'opening_hours' => 'business_opening_hours',
            'price_range' => 'business_price_range',
            'whatsapp_prefill' => 'business_whatsapp_message',
        ];

        $fallback = setting_value("business.{$key}", $default ?? config("seo.business.{$key}"));

        return setting_value($settingKeys[$key] ?? "business_{$key}", $fallback);
    }
}

if (! function_exists('social_links')) {
    function social_links(): array
    {
        return Setting::publicSocialLinks();
    }
}
