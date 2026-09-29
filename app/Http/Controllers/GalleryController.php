<?php

namespace App\Http\Controllers;

use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Models\SiteSetting;
use Illuminate\View\View;

class GalleryController extends Controller
{
    /**
     * Display the public gallery page with categorized images.
     */
    public function index(): View
    {
        $categories = GalleryCategory::query()
            ->active()
            ->withCount(['images' => fn ($query) => $query->active()])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $images = GalleryImage::query()
            ->active()
            ->with('category')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('gallery', [
            'siteSetting' => SiteSetting::query()->first(),
            'categories' => $categories,
            'images' => $images,
        ]);
    }
}
