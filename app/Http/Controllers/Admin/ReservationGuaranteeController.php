<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReservationGuarantee;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ReservationGuaranteeController extends Controller
{
    /**
     * Display a listing of reservation guarantees and section headers.
     */
    public function index(): View
    {
        ReservationGuarantee::ensureTableExists();

        $tableExists = Schema::hasTable('reservation_guarantees');

        if ($tableExists && ReservationGuarantee::query()->count() === 0) {
            ReservationGuarantee::seedDefaults();
        }

        $guarantees = $tableExists
            ? ReservationGuarantee::query()->orderBy('sort_order')->orderBy('id')->get()
            : ReservationGuarantee::defaultGuarantees();

        return view('admin.reservation-guarantees.index', [
            'guarantees' => $guarantees,
            'siteSetting' => SiteSetting::firstOrCreate(['id' => 1]),
            'availableIcons' => ReservationGuarantee::availableIcons(),
            'tableMissing' => ! $tableExists,
        ]);
    }

    /**
     * Restore or seed default reservation guarantees.
     */
    public function seedDefaults(): RedirectResponse
    {
        ReservationGuarantee::seedDefaults();

        return redirect()->route('admin.reservation-guarantees.index')
            ->with('success', 'Mevcut varsayılan rezervasyon avantajları başarıyla yüklendi.');
    }

    /**
     * Store a newly created reservation guarantee.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'icon' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'description_en' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = ! empty($validated['is_active']);

        ReservationGuarantee::create($validated);

        return redirect()->route('admin.reservation-guarantees.index')
            ->with('success', 'Yeni taahhüt / içerik maddesi başarıyla eklendi.');
    }

    /**
     * Batch update multiple reservation guarantees.
     */
    public function updateBatch(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'guarantees' => ['required', 'array'],
            'guarantees.*.icon' => ['required', 'string', 'max:50'],
            'guarantees.*.title' => ['required', 'string', 'max:255'],
            'guarantees.*.title_en' => ['nullable', 'string', 'max:255'],
            'guarantees.*.description' => ['nullable', 'string', 'max:1000'],
            'guarantees.*.description_en' => ['nullable', 'string', 'max:1000'],
            'guarantees.*.sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'guarantees.*.is_active' => ['sometimes', 'boolean'],
        ]);

        foreach ($validated['guarantees'] as $id => $data) {
            $guarantee = ReservationGuarantee::find($id);
            if ($guarantee) {
                $data['is_active'] = ! empty($data['is_active']);
                $guarantee->update($data);
            }
        }

        return redirect()->route('admin.reservation-guarantees.index')
            ->with('success', 'Tüm içerikler başarıyla güncellendi.');
    }

    /**
     * Update section headers (eyebrow and title).
     */
    public function updateSection(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'guarantees_eyebrow' => ['nullable', 'string', 'max:255'],
            'guarantees_title' => ['required', 'string', 'max:255'],
            'guarantees_eyebrow_en' => ['nullable', 'string', 'max:255'],
            'guarantees_title_en' => ['nullable', 'string', 'max:255'],
        ]);

        $siteSetting = SiteSetting::firstOrCreate(['id' => 1]);
        $siteSetting->update($validated);

        return redirect()->route('admin.reservation-guarantees.index')
            ->with('success', 'AURA Taahhüdü bölüm başlıkları başarıyla güncellendi.');
    }

    /**
     * Remove the specified guarantee.
     */
    public function destroy(ReservationGuarantee $guarantee): RedirectResponse
    {
        $guarantee->delete();

        return redirect()->route('admin.reservation-guarantees.index')
            ->with('success', 'İçerik maddesi başarıyla silindi.');
    }
}
