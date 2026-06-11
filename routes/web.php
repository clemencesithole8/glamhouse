<?php

use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminMediaAssetController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminSecurityController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Pdf\BookingPdfController;
use App\Http\Controllers\Seo\RobotsController;
use App\Http\Controllers\Seo\SitemapController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', RobotsController::class)->name('robots');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/picture-perfect', [PageController::class, 'picturePerfect'])->name('picturePerfect');
Route::get('/portfolio', [PageController::class, 'portfolio'])->name('portfolio');
Route::get('/policies', [PageController::class, 'policies'])->name('policies');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

Route::get('/availability', [AvailabilityController::class, 'index'])->name('availability.index');

// PDF download for signed links, the booking owner, or admins.
Route::get('/booking/{bookingId}/pdf', [BookingPdfController::class, 'show'])->name('booking.pdf');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/security', [AdminSecurityController::class, 'edit'])->name('security.edit');
    Route::patch('/security/profile', [AdminSecurityController::class, 'updateProfile'])->name('security.profile.update');
    Route::put('/security/password', [AdminSecurityController::class, 'updatePassword'])->name('security.password.update');

    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{bookingId}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{bookingId}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.status');
    Route::post('/bookings/{bookingId}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');

    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::post('/bookings/{bookingId}/payments', [AdminPaymentController::class, 'store'])->name('payments.store');

    Route::get('/media-assets', [AdminMediaAssetController::class, 'index'])->name('media-assets.index');
    Route::get('/media-assets/create', [AdminMediaAssetController::class, 'create'])->name('media-assets.create');
    Route::post('/media-assets', [AdminMediaAssetController::class, 'store'])->name('media-assets.store');
    Route::get('/media-assets/{mediaAsset}/edit', [AdminMediaAssetController::class, 'edit'])->name('media-assets.edit');
    Route::put('/media-assets/{mediaAsset}', [AdminMediaAssetController::class, 'update'])->name('media-assets.update');
    Route::delete('/media-assets/{mediaAsset}', [AdminMediaAssetController::class, 'destroy'])->name('media-assets.destroy');
});

require __DIR__.'/auth.php';
