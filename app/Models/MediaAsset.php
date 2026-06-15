<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MediaAsset extends Model
{
    protected $fillable = [
        'key',
        'path',
        'webp_path',
        'thumbnail_path',
        'disk',
        'title',
        'alt',
        'width',
        'height',
        'mime_type',
        'size',
        'original_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function url(string $variant = 'optimized'): string
    {
        $path = match ($variant) {
            'thumbnail' => $this->thumbnail_path ?: $this->webp_path ?: $this->path,
            'original' => $this->path,
            default => $this->webp_path ?: $this->path,
        };

        return Storage::disk($this->disk)->url($path);
    }

    public function storagePaths(): array
    {
        return array_values(array_filter([
            $this->path,
            $this->webp_path,
            $this->thumbnail_path,
        ]));
    }
}
