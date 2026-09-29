<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AmenityController extends Controller
{
    /**
     * Display a listing of amenities and the section settings.
     */
    public function index(): View
    {
        return view('admin.amenities.index', [
            'amenities' => Amenity::query()->orderBy('sort_order')->orderBy('id')->get(),
            'siteSetting' => SiteSetting::firstOrCreate(['id' => 1]),
            'availableIcons' => Amenity::availableIcons(),
        ]);
    }

    /**
     * Store a newly created amenity.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'icon' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'description_en' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = ! empty($validated['is_active']);

        Amenity::create($validated);

        return back()->with('success', 'Yeni olanak başarıyla eklendi.');
    }

    /**
     * Batch update multiple amenities.
     */
    public function updateBatch(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amenities' => ['required', 'array'],
            'amenities.*.icon' => ['required', 'string', 'max:50'],
            'amenities.*.title' => ['required', 'string', 'max:255'],
            'amenities.*.title_en' => ['nullable', 'string', 'max:255'],
            'amenities.*.description' => ['nullable', 'string', 'max:1000'],
            'amenities.*.description_en' => ['nullable', 'string', 'max:1000'],
            'amenities.*.sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'amenities.*.is_active' => ['sometimes', 'boolean'],
        ]);

        foreach ($validated['amenities'] as $id => $data) {
            $amenity = Amenity::find($id);
            if ($amenity) {
                $data['is_active'] = ! empty($data['is_active']);
                $amenity->update($data);
            }
        }

        return back()->with('success', 'Tüm olanaklar başarıyla güncellendi.');
    }

    /**
     * Update section headers (eyebrow, title, subtitle).
     */
    public function updateSection(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amenities_eyebrow' => ['nullable', 'string', 'max:255'],
            'amenities_title' => ['required', 'string', 'max:255'],
            'amenities_subtitle' => ['nullable', 'string', 'max:1000'],
            'amenities_eyebrow_en' => ['nullable', 'string', 'max:255'],
            'amenities_title_en' => ['nullable', 'string', 'max:255'],
            'amenities_subtitle_en' => ['nullable', 'string', 'max:1000'],
        ]);

        $siteSetting = SiteSetting::firstOrFail();
        $siteSetting->update($validated);

        return back()->with('success', 'Olanaklar bölüm başlığı ve açıklaması güncellendi.');
    }

    /**
     * Remove the specified amenity.
     */
    public function destroy(Amenity $amenity): RedirectResponse
    {
        $amenity->delete();

        return back()->with('success', 'Olanak silindi.');
    }
}
