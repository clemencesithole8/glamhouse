<?php

use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminAvailabilityBlockController;
use App\Http\Controllers\Admin\AdminAuditLogController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminMediaAssetController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminPortfolioItemController;
use App\Http\Controllers\Admin\AdminSecurityController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminSocialLinkController;
use App\Http\Controllers\Admin\AdminTestimonialController;
use App\Http\Controllers\Admin\AdminTimeSlotController;
use App\Http\Controllers\Admin\AdminTwoFactorChallengeController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerBookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Pdf\BookingPdfController;
use App\Http\Controllers\Pdf\PaymentReceiptPdfController;
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
Route::get('/payments/{paymentId}/receipt', [PaymentReceiptPdfController::class, 'show'])->name('payment.receipt');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/dashboard/bookings/{bookingId}/reschedule', [CustomerBookingController::class, 'requestReschedule'])->name('dashboard.bookings.reschedule');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin', 'admin.2fa'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/two-factor-challenge', [AdminTwoFactorChallengeController::class, 'show'])->name('two-factor.challenge');
    Route::post('/two-factor-challenge', [AdminTwoFactorChallengeController::class, 'verify'])->name('two-factor.verify');

    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/security', [AdminSecurityController::class, 'edit'])->name('security.edit');
    Route::patch('/security/profile', [AdminSecurityController::class, 'updateProfile'])->name('security.profile.update');
    Route::put('/security/password', [AdminSecurityController::class, 'updatePassword'])->name('security.password.update');
    Route::post('/security/two-factor/prepare', [AdminSecurityController::class, 'prepareTwoFactor'])->name('security.two-factor.prepare');
    Route::post('/security/two-factor/enable', [AdminSecurityController::class, 'enableTwoFactor'])->name('security.two-factor.enable');
    Route::post('/security/two-factor/recovery-codes', [AdminSecurityController::class, 'regenerateRecoveryCodes'])->name('security.two-factor.recovery-codes');
    Route::delete('/security/two-factor', [AdminSecurityController::class, 'disableTwoFactor'])->name('security.two-factor.disable');

    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{bookingId}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{bookingId}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.status');
    Route::post('/bookings/{bookingId}/financials', [AdminBookingController::class, 'updateFinancials'])->name('bookings.financials');
    Route::post('/bookings/{bookingId}/reschedule', [AdminBookingController::class, 'reschedule'])->name('bookings.reschedule');
    Route::post('/bookings/{bookingId}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');

    Route::get('/settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    Route::resource('services', AdminServiceController::class)->except('show');
    Route::resource('time-slots', AdminTimeSlotController::class)->except('show');
    Route::resource('portfolio-items', AdminPortfolioItemController::class)->except('show');
    Route::resource('testimonials', AdminTestimonialController::class)->except('show');
    Route::resource('social-links', AdminSocialLinkController::class)->except('show');
    Route::resource('availability-blocks', AdminAvailabilityBlockController::class)->except('show');
    Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])->name('audit-logs.index');

    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/export', [AdminPaymentController::class, 'exportCsv'])->name('payments.export');
    Route::post('/bookings/{bookingId}/payments', [AdminPaymentController::class, 'store'])->name('payments.store');

    Route::get('/media-assets', [AdminMediaAssetController::class, 'index'])->name('media-assets.index');
    Route::get('/media-assets/create', [AdminMediaAssetController::class, 'create'])->name('media-assets.create');
    Route::post('/media-assets', [AdminMediaAssetController::class, 'store'])->name('media-assets.store');
    Route::get('/media-assets/{mediaAsset}/edit', [AdminMediaAssetController::class, 'edit'])->name('media-assets.edit');
    Route::put('/media-assets/{mediaAsset}', [AdminMediaAssetController::class, 'update'])->name('media-assets.update');
    Route::delete('/media-assets/{mediaAsset}', [AdminMediaAssetController::class, 'destroy'])->name('media-assets.destroy');
});

require __DIR__.'/auth.php';
