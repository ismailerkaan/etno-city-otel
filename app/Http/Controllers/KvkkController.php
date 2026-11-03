<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\View\View;

class KvkkController extends Controller
{
    /**
     * Display the KVKK privacy notice.
     */
    public function __invoke(): View
    {
        return view('kvkk', [
            'siteSetting' => SiteSetting::query()->first(),
        ]);
    }
}
