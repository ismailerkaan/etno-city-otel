<?php

use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\HomeAboutController;
use App\Http\Controllers\Admin\NearbyPlaceController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/language/{locale}', LocaleController::class)
    ->whereIn('locale', ['tr', 'en'])
    ->name('locale.switch');
Route::get('/reservation/rooms', fn () => view('reservation.step1'))->name('reservation.step1');
Route::get('/reservation/room-detail', fn () => view('reservation.step2'))->name('reservation.step2');
Route::get('/reservation/extras', fn () => view('reservation.step3'))->name('reservation.step3');
Route::get('/reservation/guest-details', fn () => view('reservation.step4'))->name('reservation.step4');
Route::get('/reservation/payment', fn () => view('reservation.step5'))->name('reservation.step5');

Route::get('/events/{event}', [App\Http\Controllers\EventController::class, 'show'])->name('events.show');
Route::get('/galeri', [App\Http\Controllers\GalleryController::class, 'index'])->name('gallery');
Route::get('/gallery', fn () => redirect()->route('gallery'));

Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('/login', [AuthController::class, 'create'])->name('login');
        Route::post('/login', [AuthController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('login.store');
    });

    Route::middleware(['auth', EnsureUserIsAdmin::class])->group(function (): void {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::get('/site-settings', [SiteSettingController::class, 'edit'])->name('site-settings.edit');
        Route::put('/site-settings', [SiteSettingController::class, 'update'])->name('site-settings.update');
        Route::put('/hero-slides/batch', [HeroSlideController::class, 'updateBatch'])->name('hero-slides.batch-update');
        Route::resource('hero-slides', HeroSlideController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('/home-about', [HomeAboutController::class, 'edit'])->name('home-about.edit');
        Route::put('/home-about', [HomeAboutController::class, 'update'])->name('home-about.update');
        Route::get('/amenities', [AmenityController::class, 'index'])->name('amenities.index');
        Route::post('/amenities', [AmenityController::class, 'store'])->name('amenities.store');
        Route::put('/amenities/batch', [AmenityController::class, 'updateBatch'])->name('amenities.batch-update');
        Route::put('/amenities/section', [AmenityController::class, 'updateSection'])->name('amenities.section-update');
        Route::delete('/amenities/{amenity}', [AmenityController::class, 'destroy'])->name('amenities.destroy');
        Route::put('/events/section', [EventController::class, 'updateSection'])->name('events.section-update');
        Route::patch('/events/{event}/toggle-home', [EventController::class, 'toggleHome'])->name('events.toggle-home');
        Route::resource('events', EventController::class);
        Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
        Route::post('/gallery/categories', [GalleryController::class, 'storeCategory'])->name('gallery.categories.store');
        Route::put('/gallery/categories/{category}', [GalleryController::class, 'updateCategory'])->name('gallery.categories.update');
        Route::delete('/gallery/categories/{category}', [GalleryController::class, 'destroyCategory'])->name('gallery.categories.destroy');
        Route::post('/gallery/categories/{category}/images', [GalleryController::class, 'storeImages'])->name('gallery.images.store');
        Route::put('/gallery/images/batch', [GalleryController::class, 'updateImages'])->name('gallery.images.batch-update');
        Route::delete('/gallery/images/{image}', [GalleryController::class, 'destroyImage'])->name('gallery.images.destroy');
        Route::put('/gallery/section', [GalleryController::class, 'updateSection'])->name('gallery.section-update');
        Route::put('/testimonials/section', [TestimonialController::class, 'updateSection'])->name('testimonials.section-update');
        Route::patch('/testimonials/{testimonial}/toggle-status', [TestimonialController::class, 'toggleStatus'])->name('testimonials.toggle-status');
        Route::resource('testimonials', TestimonialController::class);
        Route::put('/nearby-places/section', [NearbyPlaceController::class, 'updateSection'])->name('nearby-places.section-update');
        Route::patch('/nearby-places/{nearbyPlace}/toggle-status', [NearbyPlaceController::class, 'toggleStatus'])->name('nearby-places.toggle-status');
        Route::resource('nearby-places', NearbyPlaceController::class);
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    });
});
