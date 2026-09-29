<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHeroSlidesRequest;
use App\Http\Requests\Admin\UpdateHeroSlideRequest;
use App\Models\HeroSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HeroSlideController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.hero-slides.index', [
            'heroSlides' => HeroSlide::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHeroSlidesRequest $request): RedirectResponse
    {
        $nextSortOrder = ((int) HeroSlide::query()->max('sort_order')) + 1;

        foreach ($request->file('images') as $image) {
            HeroSlide::query()->create([
                'image_path' => $image->store('hero-slides', 'public'),
                'sort_order' => $nextSortOrder++,
                'is_active' => true,
            ]);
        }

        return back()->with('success', 'Slider görselleri yüklendi.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHeroSlideRequest $request, HeroSlide $heroSlide): RedirectResponse
    {
        $heroSlide->update($request->validated());

        return back()->with('success', 'Slider görseli güncellendi.');
    }

    /**
     * Batch update multiple hero slides at once.
     */
    public function updateBatch(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'slides' => ['required', 'array'],
            'slides.*.title' => ['nullable', 'string', 'max:255'],
            'slides.*.title_en' => ['nullable', 'string', 'max:255'],
            'slides.*.description' => ['nullable', 'string', 'max:1000'],
            'slides.*.description_en' => ['nullable', 'string', 'max:1000'],
            'slides.*.sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'slides.*.is_active' => ['sometimes', 'boolean'],
        ]);

        foreach ($validated['slides'] as $id => $slideData) {
            $slide = HeroSlide::find($id);
            if ($slide) {
                $slideData['is_active'] = ! empty($slideData['is_active']);
                $slide->update($slideData);
            }
        }

        return back()->with('success', 'Tüm slider içerikleri başarıyla kaydedildi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HeroSlide $heroSlide): RedirectResponse
    {
        Storage::disk('public')->delete($heroSlide->image_path);
        $heroSlide->delete();

        return back()->with('success', 'Slider görseli silindi.');
    }
}
