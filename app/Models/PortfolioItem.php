<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PortfolioItem extends Model
{
    protected $fillable = [
        'title',
        'alt_text',
        'category',
        'image_path',
        'webp_path',
        'thumbnail_path',
        'before_image_path',
        'after_image_path',
        'width',
        'height',
        'is_featured',
        'is_active',
        'sort_order',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function scopeVisible(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where(function (Builder $query): void {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function imageUrl(string $variant = 'optimized'): string
    {
        $path = match ($variant) {
            'thumbnail' => $this->thumbnail_path ?: $this->webp_path ?: $this->image_path,
            'original' => $this->image_path,
            default => $this->webp_path ?: $this->image_path,
        };

        return Storage::disk('public')->url($path);
    }

    public function imageAlt(): string
    {
        return $this->alt_text ?: $this->title ?: 'Portfolio image';
    }

    public function storagePaths(): array
    {
        return array_values(array_filter([
            $this->image_path,
            $this->webp_path,
            $this->thumbnail_path,
            $this->before_image_path,
            $this->after_image_path,
        ]));
    }
}
