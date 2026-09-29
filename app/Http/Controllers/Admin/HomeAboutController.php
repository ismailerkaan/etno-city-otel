<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HomeAboutController extends Controller
{
    /**
     * Show the form for editing the home about section.
     */
    public function edit(): View
    {
        $siteSetting = SiteSetting::firstOrCreate(
            ['id' => 1],
            [
                'hotel_name' => 'EtnoCity Otel',
                'about_eyebrow' => "EtnoCity Otel'e Hoş Geldiniz",
                'about_title' => 'Deniz ve Yaşayan Taşla Uyum İçinde Yaratıldı',
                'suite_count' => 24,
            ]
        );

        return view('admin.home-about.edit', compact('siteSetting'));
    }

    /**
     * Update the home about section.
     */
    public function update(Request $request): RedirectResponse
    {
        $siteSetting = SiteSetting::firstOrFail();

        $validated = $request->validate([
            'about_eyebrow' => ['nullable', 'string', 'max:255'],
            'about_title' => ['required', 'string', 'max:255'],
            'about_primary_text' => ['nullable', 'string'],
            'about_secondary_text' => ['nullable', 'string'],
            'about_eyebrow_en' => ['nullable', 'string', 'max:255'],
            'about_title_en' => ['nullable', 'string', 'max:255'],
            'about_primary_text_en' => ['nullable', 'string'],
            'about_secondary_text_en' => ['nullable', 'string'],
            'suite_count' => ['required', 'integer', 'min:0', 'max:9999'],
            'award_badge_title' => ['nullable', 'string', 'max:255'],
            'award_badge_subtitle' => ['nullable', 'string', 'max:255'],
            'award_badge_title_en' => ['nullable', 'string', 'max:255'],
            'award_badge_subtitle_en' => ['nullable', 'string', 'max:255'],
            'about_image' => ['nullable', 'image', 'max:5120'],
            'about_image_url' => ['nullable', 'string', 'max:2048'],
        ]);

        if ($request->hasFile('about_image')) {
            // Delete old uploaded file if not an external URL
            if ($siteSetting->about_image_url && ! str_starts_with($siteSetting->about_image_url, 'http')) {
                Storage::disk('public')->delete($siteSetting->about_image_url);
            }
            $validated['about_image_url'] = $request->file('about_image')->store('about', 'public');
        }

        unset($validated['about_image']);

        $siteSetting->update($validated);

        return back()->with('success', 'Otel tanıtım bölümü başarıyla güncellendi.');
    }
}
