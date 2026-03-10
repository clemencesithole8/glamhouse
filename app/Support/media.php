<?php

use App\Models\MediaAsset;

if (! function_exists('media_url')) {
    function media_url(string $key, ?string $fallback = null): string
    {
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

        if ($keys !== []) {
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
