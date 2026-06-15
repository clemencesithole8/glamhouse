<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Throwable;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function valueFor(string $key, ?string $default = null): ?string
    {
        try {
            $value = static::query()->where('key', $key)->value('value');

            return $value === null || $value === '' ? $default : $value;
        } catch (Throwable) {
            return $default;
        }
    }

    public static function valuesWithDefaults(array $defaults): array
    {
        try {
            $values = static::query()
                ->whereIn('key', array_keys($defaults))
                ->pluck('value', 'key')
                ->all();
        } catch (Throwable) {
            $values = [];
        }

        return array_replace($defaults, array_filter($values, static fn ($value): bool => ! is_null($value)));
    }

    public static function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            static::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }

    public static function publicSocialLinks(): array
    {
        try {
            if (Schema::hasTable('social_links')) {
                $links = SocialLink::query()
                    ->where('is_active', true)
                    ->orderBy('display_order')
                    ->orderBy('platform')
                    ->get()
                    ->mapWithKeys(function (SocialLink $link): array {
                        $key = strtolower($link->platform);

                        return [$key => [
                            'label' => $link->label ?: ucfirst($link->platform),
                            'url' => $link->url,
                        ]];
                    })
                    ->all();

                if ($links !== []) {
                    return $links;
                }
            }
        } catch (Throwable) {
            //
        }

        $platforms = [
            'instagram' => ['Instagram', 'social_instagram_url'],
            'tiktok' => ['TikTok', 'social_tiktok_url'],
            'youtube' => ['YouTube', 'social_youtube_url'],
            'facebook' => ['Facebook', 'social_facebook_url'],
            'x' => ['X', 'social_x_url'],
        ];

        $links = [];

        foreach ($platforms as $key => [$label, $settingKey]) {
            $url = trim((string) static::valueFor($settingKey, config("seo.social.{$key}_url", '')));

            if ($url !== '') {
                $links[$key] = [
                    'label' => $label,
                    'url' => $url,
                ];
            }
        }

        return $links;
    }
}
