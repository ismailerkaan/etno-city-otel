<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NearbyPlace;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NearbyPlaceController extends Controller
{
    /**
     * Display a listing of nearby places and the location section settings.
     */
    public function index(): View
    {
        return view('admin.nearby-places.index', [
            'places' => NearbyPlace::query()->orderBy('sort_order')->orderBy('id')->get(),
            'siteSetting' => SiteSetting::firstOrCreate(['id' => 1]),
            'availableIcons' => NearbyPlace::availableIcons(),
            'nextSortOrder' => ((int) NearbyPlace::max('sort_order')) + 1,
        ]);
    }

    /**
     * Store a newly created nearby place.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'icon' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'description_en' => ['nullable', 'string', 'max:500'],
            'distance' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = ! empty($validated['is_active']);

        NearbyPlace::create($validated);

        return back()->with('success', 'Yeni yakın yer / ulaşım noktası eklendi.');
    }

    /**
     * Show the form for editing the specified nearby place.
     */
    public function edit(NearbyPlace $nearbyPlace): View
    {
        return view('admin.nearby-places.edit', [
            'place' => $nearbyPlace,
            'availableIcons' => NearbyPlace::availableIcons(),
        ]);
    }

    /**
     * Update the specified nearby place.
     */
    public function update(Request $request, NearbyPlace $nearbyPlace): RedirectResponse
    {
        $validated = $request->validate([
            'icon' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'description_en' => ['nullable', 'string', 'max:500'],
            'distance' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = ! empty($validated['is_active']);

        $nearbyPlace->update($validated);

        return redirect()->route('admin.nearby-places.index')->with('success', 'Yakın yer bilgisi güncellendi.');
    }

    /**
     * Remove the specified nearby place.
     */
    public function destroy(NearbyPlace $nearbyPlace): RedirectResponse
    {
        $nearbyPlace->delete();

        return back()->with('success', 'Yakın yer silindi.');
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus(NearbyPlace $nearbyPlace): RedirectResponse
    {
        $nearbyPlace->update(['is_active' => ! $nearbyPlace->is_active]);

        $status = $nearbyPlace->is_active ? 'aktif edildi' : 'pasife alındı';

        return back()->with('success', "Durum güncellendi ({$status}).");
    }

    /**
     * Update location & map section headers and map embed settings.
     */
    public function updateSection(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'location_eyebrow' => ['nullable', 'string', 'max:255'],
            'location_eyebrow_en' => ['nullable', 'string', 'max:255'],
            'location_title' => ['required', 'string', 'max:255'],
            'location_title_en' => ['nullable', 'string', 'max:255'],
            'location_description' => ['nullable', 'string', 'max:1500'],
            'location_description_en' => ['nullable', 'string', 'max:1500'],
            'location_map_embed_url' => ['nullable', 'string', 'max:2000'],
            'location_coordinates' => ['nullable', 'string', 'max:255'],
            'location_address' => ['nullable', 'string', 'max:255'],
            'location_pin_label' => ['nullable', 'string', 'max:255'],
        ]);

        $siteSetting = SiteSetting::firstOrFail();
        $siteSetting->update($validated);

        return back()->with('success', 'Harita ve konum bölümü ayarları kaydedildi.');
    }
}
