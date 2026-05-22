<?php

use App\Models\MediaAsset;
use Illuminate\Support\Facades\Schema;

if (! function_exists('media_assets_table_exists')) {
    function media_assets_table_exists(): bool
    {
        static $exists;

        if ($exists === null) {
            $exists = Schema::hasTable('media_assets');
        }

        return $exists;
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
     * @return array{key:?string,url:string,alt:string}
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
                    'alt' => $asset->alt ?: $defaultAlt,
                ];
            }
        }

        if ($fallbacks !== []) {
            $fallback = $fallbacks[array_rand($fallbacks)];

            return [
                'key' => null,
                'url' => $fallback,
                'alt' => $defaultAlt,
            ];
        }

        return [
            'key' => null,
            'url' => '',
            'alt' => $defaultAlt,
        ];
    }
}
