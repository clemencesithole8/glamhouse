<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use App\Models\PortfolioItem;
use App\Models\Service;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $latestContentUpdate = collect([
            Schema::hasTable('services') ? Service::query()->max('updated_at') : null,
            Schema::hasTable('portfolio_items') ? PortfolioItem::query()->max('updated_at') : null,
        ])->filter()->max();

        $lastmod = $latestContentUpdate
            ? Carbon::parse($latestContentUpdate)->toAtomString()
            : now()->toAtomString();

        $pages = [
            ['loc' => route('home'), 'priority' => '1.0', 'changefreq' => 'weekly', 'lastmod' => $lastmod],
            ['loc' => route('about'), 'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => $lastmod],
            ['loc' => route('services'), 'priority' => '0.9', 'changefreq' => 'weekly', 'lastmod' => $lastmod],
            ['loc' => route('picturePerfect'), 'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => $lastmod],
            ['loc' => route('portfolio'), 'priority' => '0.9', 'changefreq' => 'weekly', 'lastmod' => $lastmod],
            ['loc' => route('contact'), 'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => $lastmod],
            ['loc' => route('faq'), 'priority' => '0.6', 'changefreq' => 'monthly', 'lastmod' => $lastmod],
            ['loc' => route('policies'), 'priority' => '0.5', 'changefreq' => 'yearly', 'lastmod' => $lastmod],
        ];

        return response()
            ->view('seo.sitemap', ['pages' => $pages])
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
