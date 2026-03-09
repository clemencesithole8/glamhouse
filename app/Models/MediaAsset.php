<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MediaAsset extends Model
{
    protected $fillable = [
        'key','path','disk','title','alt','width','height','is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function url(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }
}