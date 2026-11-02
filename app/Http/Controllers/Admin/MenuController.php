<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\SiteSetting;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Display menu categories, items and the QR code card.
     */
    public function index(): View
    {
        $categories = MenuCategory::query()
            ->with('items')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.menu.index', [
            'categories' => $categories,
            'siteSetting' => SiteSetting::query()->first(),
            'menuUrl' => route('menu'),
            'qrSvg' => $this->qrSvg(route('menu')),
            'nextCategorySortOrder' => ((int) MenuCategory::max('sort_order')) + 1,
        ]);
    }

    /**
     * Download the public menu QR code as an SVG file.
     */
    public function qrCode(): Response
    {
        return response($this->qrSvg(route('menu'), 1024), 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="menu-qr-kod.svg"',
        ]);
    }

    /**
     * Store a newly created menu category.
     */
    public function storeCategory(Request $request): RedirectResponse
    {
        MenuCategory::create($this->validateCategory($request));

        return back()->with('success', 'Yeni menü kategorisi eklendi.');
    }

    /**
     * Update the specified menu category.
     */
    public function updateCategory(Request $request, MenuCategory $category): RedirectResponse
    {
        $category->update($this->validateCategory($request));

        return back()->with('success', 'Menü kategorisi güncellendi.');
    }

    /**
     * Remove the specified menu category with its items.
     */
    public function destroyCategory(MenuCategory $category): RedirectResponse
    {
        $category->delete();

        return back()->with('success', 'Menü kategorisi ve içindeki ürünler silindi.');
    }

    /**
     * Store a newly created menu item.
     */
    public function storeItem(Request $request): RedirectResponse
    {
        MenuItem::create($this->validateItem($request));

        return back()->with('success', 'Yeni menü ürünü eklendi.');
    }

    /**
     * Show the form for editing the specified menu item.
     */
    public function editItem(MenuItem $item): View
    {
        return view('admin.menu.edit-item', [
            'item' => $item,
            'categories' => MenuCategory::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    /**
     * Update the specified menu item.
     */
    public function updateItem(Request $request, MenuItem $item): RedirectResponse
    {
        $item->update($this->validateItem($request));

        return redirect()->route('admin.menu.index')->with('success', 'Menü ürünü güncellendi.');
    }

    /**
     * Toggle the active status of a menu item.
     */
    public function toggleItemStatus(MenuItem $item): RedirectResponse
    {
        $item->update(['is_active' => ! $item->is_active]);

        $status = $item->is_active ? 'aktif edildi' : 'pasife alındı';

        return back()->with('success', "Durum güncellendi ({$status}).");
    }

    /**
     * Remove the specified menu item.
     */
    public function destroyItem(MenuItem $item): RedirectResponse
    {
        $item->delete();

        return back()->with('success', 'Menü ürünü silindi.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateCategory(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = ! empty($validated['is_active']);

        return $validated;
    }

    /**
     * @return array<string, mixed>
     */
    private function validateItem(Request $request): array
    {
        $validated = $request->validate([
            'menu_category_id' => ['required', 'integer', 'exists:menu_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'description_en' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = ! empty($validated['is_active']);

        return $validated;
    }

    private function qrSvg(string $url, int $size = 320): string
    {
        $writer = new Writer(new ImageRenderer(new RendererStyle($size, 2), new SvgImageBackEnd));

        return $writer->writeString($url);
    }
}
