<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HmsSetting;
use App\Models\Reservation;
use App\Services\Hms\HmsClient;
use App\Services\Hms\HmsException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HmsSettingController extends Controller
{
    public function edit(HmsClient $hms): View
    {
        $stored = HmsSetting::current();

        return view('admin.hms.edit', [
            'setting' => $stored,
            'settings' => $hms->settings(),
            'usingEnv' => $stored === null,
            'failedReservations' => Reservation::query()->hmsSyncFailed()->latest('id')->get(),
        ]);
    }

    public function update(Request $request, HmsClient $hms): RedirectResponse
    {
        $validated = $this->validated($request, $hms);

        $setting = HmsSetting::current() ?? new HmsSetting;
        $setting->fill($validated)->save();

        return back()->with('success', 'HMS ayarları kaydedildi.');
    }

    /**
     * Test the submitted credentials without saving them.
     */
    public function test(Request $request, HmsClient $hms): RedirectResponse
    {
        $validated = $this->validated($request, $hms);

        try {
            $roomTypes = $hms->withSettings([...$validated, 'enabled' => true])->testConnection();
            $result = 'Bağlantı başarılı. HMS\'te '.count($roomTypes).' oda tipi bulundu: '.implode(', ', array_slice($roomTypes, 0, 10));
            $success = true;
        } catch (HmsException $exception) {
            $result = 'Bağlantı başarısız: '.$exception->getMessage();
            $success = false;
        }

        HmsSetting::current()?->update(['last_tested_at' => now(), 'last_test_result' => $result]);

        return back()
            ->withInput($request->except('api_key', 'api_secret'))
            ->with($success ? 'success' : 'hms_test_error', $result);
    }

    /**
     * @return array{enabled: bool, base_url: string, hotel_code: string, api_key: string, api_secret: string}
     */
    private function validated(Request $request, HmsClient $hms): array
    {
        $current = $hms->settings();

        $validated = $request->validate([
            'enabled' => ['sometimes', 'boolean'],
            'base_url' => ['required', 'url:https', 'max:255'],
            'hotel_code' => ['required', 'string', 'max:50'],
            // Left blank: keep the stored value (secrets are never shown back in the form)
            'api_key' => [filled($current['api_key']) ? 'nullable' : 'required', 'string', 'max:500'],
            'api_secret' => [filled($current['api_secret']) ? 'nullable' : 'required', 'string', 'max:500'],
        ]);

        return [
            'enabled' => $request->boolean('enabled'),
            'base_url' => rtrim($validated['base_url'], '/'),
            'hotel_code' => trim($validated['hotel_code']),
            'api_key' => trim($validated['api_key'] ?? '') ?: (string) $current['api_key'],
            'api_secret' => trim($validated['api_secret'] ?? '') ?: (string) $current['api_secret'],
        ];
    }
}
