<?php

namespace App\Providers;

use App\Http\Controllers\Admin\AdminSettingController;
use App\Models\Setting;
use App\Models\SocialLink;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! $this->app->runningInConsole() || $this->app->runningUnitTests()) {
            $this->applyDatabaseSettings();
        }

        $this->sharePublicSocialLinks();

        if (config('app.force_https')) {
            URL::forceScheme('https');
        }
    }

    private function applyDatabaseSettings(): void
    {
        $settings = Setting::valuesWithDefaults(AdminSettingController::DEFAULTS);

        Config::set('seo.business.phone', $settings['business_phone']);
        Config::set('seo.business.email', $settings['business_email']);
        Config::set('seo.business.location', $settings['business_location']);
        Config::set('seo.default.title', $settings['seo_default_title']);
        Config::set('seo.default.description', $settings['seo_default_description']);
        Config::set('seo.default.keywords', $settings['seo_default_keywords']);
        Config::set('seo.default.image', $settings['seo_default_image']);
        Config::set('seo.default.robots', $settings['seo_default_robots']);
        Config::set('glamhouse.whatsapp_message', $settings['business_whatsapp_message']);
        Config::set('glamhouse.social_urls', [
            'instagram' => $settings['social_instagram_url'],
            'facebook' => $settings['social_facebook_url'],
            'tiktok' => $settings['social_tiktok_url'],
            'youtube' => $settings['social_youtube_url'],
            'x' => $settings['social_x_url'],
        ]);
    }

    private function sharePublicSocialLinks(): void
    {
        View::composer(['layouts.public', 'pages.contact'], function ($view): void {
            try {
                $links = SocialLink::query()
                    ->where('is_active', true)
                    ->orderBy('display_order')
                    ->orderBy('platform')
                    ->get();
            } catch (Throwable) {
                $links = collect();
            }

            $view->with('socialLinks', $links);
        });
    }
}
