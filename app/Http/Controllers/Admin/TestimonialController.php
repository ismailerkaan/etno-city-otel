<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the testimonials.
     */
    public function index(): View
    {
        return view('admin.testimonials.index', [
            'testimonials' => Testimonial::query()->orderBy('sort_order')->orderBy('id')->get(),
            'siteSetting' => SiteSetting::firstOrCreate(['id' => 1]),
            'nextSortOrder' => ((int) Testimonial::max('sort_order')) + 1,
        ]);
    }

    /**
     * Store a newly created testimonial.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'author_name' => ['required', 'string', 'max:255'],
            'author_title' => ['nullable', 'string', 'max:255'],
            'author_title_en' => ['nullable', 'string', 'max:255'],
            'comment' => ['required', 'string', 'max:2000'],
            'comment_en' => ['nullable', 'string', 'max:2000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('testimonials', 'public');
        }

        Testimonial::create([
            'author_name' => $validated['author_name'],
            'author_title' => $validated['author_title'] ?? null,
            'author_title_en' => $validated['author_title_en'] ?? null,
            'comment' => $validated['comment'],
            'comment_en' => $validated['comment_en'] ?? null,
            'rating' => $validated['rating'],
            'avatar_url' => $avatarPath,
            'sort_order' => $validated['sort_order'],
            'is_active' => ! empty($validated['is_active']),
        ]);

        return back()->with('success', 'Yeni yorum başarıyla eklendi.');
    }

    /**
     * Show the form for editing the specified testimonial.
     */
    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.edit', [
            'testimonial' => $testimonial,
        ]);
    }

    /**
     * Update the specified testimonial.
     */
    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $request->validate([
            'author_name' => ['required', 'string', 'max:255'],
            'author_title' => ['nullable', 'string', 'max:255'],
            'author_title_en' => ['nullable', 'string', 'max:255'],
            'comment' => ['required', 'string', 'max:2000'],
            'comment_en' => ['nullable', 'string', 'max:2000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'remove_avatar' => ['sometimes', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $avatarPath = $testimonial->avatar_url;

        if ($request->boolean('remove_avatar') && $testimonial->avatar_url) {
            Storage::disk('public')->delete($testimonial->avatar_url);
            $avatarPath = null;
        }

        if ($request->hasFile('avatar')) {
            if ($testimonial->avatar_url) {
                Storage::disk('public')->delete($testimonial->avatar_url);
            }
            $avatarPath = $request->file('avatar')->store('testimonials', 'public');
        }

        $testimonial->update([
            'author_name' => $validated['author_name'],
            'author_title' => $validated['author_title'] ?? null,
            'author_title_en' => $validated['author_title_en'] ?? null,
            'comment' => $validated['comment'],
            'comment_en' => $validated['comment_en'] ?? null,
            'rating' => $validated['rating'],
            'avatar_url' => $avatarPath,
            'sort_order' => $validated['sort_order'],
            'is_active' => ! empty($validated['is_active']),
        ]);

        return redirect()->route('admin.testimonials.index')->with('success', 'Yorum başarıyla güncellendi.');
    }

    /**
     * Remove the specified testimonial.
     */
    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        if ($testimonial->avatar_url) {
            Storage::disk('public')->delete($testimonial->avatar_url);
        }

        $testimonial->delete();

        return back()->with('success', 'Yorum silindi.');
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update(['is_active' => ! $testimonial->is_active]);

        $status = $testimonial->is_active ? 'aktif edildi' : 'pasife alındı';

        return back()->with('success', "Yorum durumu güncellendi ({$status}).");
    }

    /**
     * Update section header and description.
     */
    public function updateSection(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'testimonials_eyebrow' => ['nullable', 'string', 'max:255'],
            'testimonials_title' => ['required', 'string', 'max:255'],
            'testimonials_description' => ['nullable', 'string', 'max:1000'],
            'testimonials_eyebrow_en' => ['nullable', 'string', 'max:255'],
            'testimonials_title_en' => ['nullable', 'string', 'max:255'],
            'testimonials_description_en' => ['nullable', 'string', 'max:1000'],
        ]);

        $siteSetting = SiteSetting::firstOrFail();
        $siteSetting->update($validated);

        return back()->with('success', 'Yorumlar bölüm başlığı ve açıklaması güncellendi.');
    }
}
