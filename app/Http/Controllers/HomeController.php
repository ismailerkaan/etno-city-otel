<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\Event;
use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Models\HeroSlide;
use App\Models\NearbyPlace;
use App\Models\RoomType;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        $siteSetting = SiteSetting::query()->first();

        return view('home', [
            'siteSetting' => $siteSetting,
            'heroSlides' => HeroSlide::query()->active()->orderBy('sort_order')->orderBy('id')->get(),
            'amenities' => Amenity::query()->active()->orderBy('sort_order')->orderBy('id')->get(),
            'events' => ($siteSetting?->events_enabled ?? true)
                ? Event::query()->forHome()->orderBy('sort_order')->orderBy('id')->get()
                : collect(),
            'galleryCategories' => GalleryCategory::query()->active()->orderBy('sort_order')->orderBy('id')->get(),
            'galleryImages' => GalleryImage::query()->active()->with('category')->orderBy('sort_order')->orderBy('id')->limit(6)->get(),
            'testimonials' => Testimonial::query()->active()->orderBy('sort_order')->orderBy('id')->get(),
            'nearbyPlaces' => NearbyPlace::query()->active()->orderBy('sort_order')->orderBy('id')->get(),
            'homeRoomTypes' => RoomType::query()->active()->where('show_on_home', true)->with('images')->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }
}
