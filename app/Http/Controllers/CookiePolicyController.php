<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\View\View;

class CookiePolicyController extends Controller
{
    /**
     * Display the cookie policy page.
     */
    public function __invoke(): View
    {
        return view('cookie-policy', [
            'siteSetting' => SiteSetting::query()->first(),
        ]);
    }
}
