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