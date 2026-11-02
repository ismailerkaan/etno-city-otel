<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\SiteSetting;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Display the public cafe & lounge menu.
     */
    public function index(): View
    {
        $categories = MenuCategory::query()
            ->active()
            ->with(['items' => fn ($query) => $query->active()])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->filter(fn (MenuCategory $category): bool => $category->items->isNotEmpty());

        return view('menu', [
            'siteSetting' => SiteSetting::query()->first(),
            'categories' => $categories,
        ]);
    }
}
