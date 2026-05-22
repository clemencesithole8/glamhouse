<?php
namespace App\Http\Controllers;

use App\Models\PortfolioItem;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class PageController extends Controller
{
    public function home() {
        return view('pages.home', [
            'services' => Schema::hasTable('services')
                ? Service::where('is_active', true)->get()
                : collect(),
            'featuredPortfolio' => Schema::hasTable('portfolio_items')
                ? PortfolioItem::where('is_featured', true)->latest()->take(8)->get()
                : collect(),
            'testimonials' => Schema::hasTable('testimonials')
                ? Testimonial::where('is_featured', true)->latest()->take(6)->get()
                : collect(),
        ]);
    }

    public function about() { return view('pages.about'); }
    public function services() {
        return view('pages.services', [
            'services' => Schema::hasTable('services')
                ? Service::where('is_active', true)->get()
                : collect(),
        ]);
    }
    public function picturePerfect() { return view('pages.picture-perfect'); }
    public function portfolio() {
        return view('pages.portfolio', [
            'items' => Schema::hasTable('portfolio_items')
                ? PortfolioItem::latest()->paginate(18)
                : new LengthAwarePaginator([], 0, 18),
        ]);
    }
    public function policies() { return view('pages.policies'); }
    public function faq() { return view('pages.faq'); }
    public function contact() { return view('pages.contact'); }
}
