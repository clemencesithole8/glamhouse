<?php
namespace App\Http\Controllers;

use App\Models\PortfolioItem;
use App\Models\Service;
use App\Models\Testimonial;

class PageController extends Controller
{
    public function home() {
        return view('pages.home', [
            'services' => Service::where('is_active', true)->get(),
            'featuredPortfolio' => PortfolioItem::where('is_featured', true)->latest()->take(8)->get(),
            'testimonials' => Testimonial::where('is_featured', true)->latest()->take(6)->get(),
        ]);
    }

    public function about() { return view('pages.about'); }
    public function services() {
        return view('pages.services', [
            'services' => Service::where('is_active', true)->get(),
        ]);
    }
    public function picturePerfect() { return view('pages.picture-perfect'); }
    public function portfolio() {
        return view('pages.portfolio', [
            'items' => PortfolioItem::latest()->paginate(18),
        ]);
    }
    public function policies() { return view('pages.policies'); }
    public function faq() { return view('pages.faq'); }
    public function contact() { return view('pages.contact'); }
}
