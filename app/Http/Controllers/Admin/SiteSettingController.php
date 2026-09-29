<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSiteSettingRequest;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.site-settings.edit', [
            'siteSetting' => SiteSetting::query()->firstOrFail(),
        ]);
    }

    public function update(UpdateSiteSettingRequest $request): RedirectResponse
    {
        $siteSetting = SiteSetting::query()->firstOrFail();
        $data = $request->validated();
        unset($data['logo'], $data['remove_logo']);

        if ($request->boolean('remove_logo')) {
            if ($siteSetting->logo_url && ! str_starts_with($siteSetting->logo_url, 'http://') && ! str_starts_with($siteSetting->logo_url, 'https://')) {
                Storage::disk('public')->delete($siteSetting->logo_url);
            }
            $data['logo_url'] = null;
        } elseif ($request->hasFile('logo')) {
            if ($siteSetting->logo_url && ! str_starts_with($siteSetting->logo_url, 'http://') && ! str_starts_with($siteSetting->logo_url, 'https://')) {
                Storage::disk('public')->delete($siteSetting->logo_url);
            }
            $data['logo_url'] = $request->file('logo')->store('logos', 'public');
        }

        $siteSetting->update($data);

        return back()->with('success', 'Site ayarları güncellendi.');
    }
}
