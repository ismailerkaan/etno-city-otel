<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GalleryController extends Controller
{
    /**
     * Display gallery management dashboard.
     */
    public function index(Request $request): View
    {
        $categories = GalleryCategory::query()->orderBy('sort_order')->orderBy('id')->get();

        $selectedCategoryId = $request->query('category_id');
        $selectedCategory = $selectedCategoryId
            ? $categories->firstWhere('id', (int) $selectedCategoryId)
            : $categories->first();

        $images = $selectedCategory
            ? $selectedCategory->images()->orderBy('sort_order')->orderBy('id')->get()
            : collect();

        return view('admin.gallery.index', [
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'images' => $images,
            'siteSetting' => SiteSetting::firstOrCreate(['id' => 1]),
        ]);
    }

    /**
     * Store a new gallery category.
     */
    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
        ]);

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (GalleryCategory::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-".$counter++;
        }

        $category = GalleryCategory::create([
            'name' => $validated['name'],
            'name_en' => $validated['name_en'] ?? null,
            'slug' => $slug,
            'sort_order' => $validated['sort_order'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.gallery.index', ['category_id' => $category->id])
            ->with('success', 'Galeri kategorisi eklendi.');
    }

    /**
     * Update a gallery category.
     */
    public function updateCategory(Request $request, GalleryCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = ! empty($validated['is_active']);

        $category->update($validated);

        return back()->with('success', 'Kategori güncellendi.');
    }

    /**
     * Delete a gallery category.
     */
    public function destroyCategory(GalleryCategory $category): RedirectResponse
    {
        foreach ($category->images as $image) {
            if ($image->image_path && ! str_starts_with($image->image_path, 'http')) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $category->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Kategori ve bağlı tüm resimler silindi.');
    }

    /**
     * Upload new images to selected category.
     */
    public function storeImages(Request $request, GalleryCategory $category): RedirectResponse
    {
        $request->validate([
            'images' => ['required', 'array'],
            'images.*' => ['image', 'max:5120'],
        ]);

        $nextSortOrder = ((int) $category->images()->max('sort_order')) + 1;

        foreach ($request->file('images') as $file) {
            $path = $file->store('gallery', 'public');
            GalleryImage::create([
                'gallery_category_id' => $category->id,
                'image_path' => $path,
                'tag' => null,
                'tag_en' => null,
                'sort_order' => $nextSortOrder++,
                'is_active' => true,
            ]);
        }

        return back()->with('success', count($request->file('images')).' adet görsel galeriye eklendi.');
    }

    /**
     * Batch update tags and order of images in category.
     */
    public function updateImages(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'images' => ['required', 'array'],
            'images.*.tag' => ['nullable', 'string', 'max:255'],
            'images.*.tag_en' => ['nullable', 'string', 'max:255'],
            'images.*.sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'images.*.is_active' => ['sometimes', 'boolean'],
        ]);

        foreach ($validated['images'] as $id => $data) {
            $image = GalleryImage::find($id);
            if ($image) {
                $data['is_active'] = ! empty($data['is_active']);
                $image->update($data);
            }
        }

        return back()->with('success', 'Tüm resim etiketleri ve sıralamaları kaydedildi.');
    }

    /**
     * Delete a single gallery image.
     */
    public function destroyImage(GalleryImage $image): RedirectResponse
    {
        if ($image->image_path && ! str_starts_with($image->image_path, 'http')) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return back()->with('success', 'Görsel silindi.');
    }

    /**
     * Update section headers for gallery.
     */
    public function updateSection(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'gallery_eyebrow' => ['nullable', 'string', 'max:255'],
            'gallery_title' => ['required', 'string', 'max:255'],
            'gallery_description' => ['nullable', 'string', 'max:1000'],
            'gallery_eyebrow_en' => ['nullable', 'string', 'max:255'],
            'gallery_title_en' => ['nullable', 'string', 'max:255'],
            'gallery_description_en' => ['nullable', 'string', 'max:1000'],
        ]);

        $siteSetting = SiteSetting::firstOrFail();
        $siteSetting->update($validated);

        return back()->with('success', 'Galeri bölüm başlıkları güncellendi.');
    }
}
