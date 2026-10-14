<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display contact page settings and incoming messages.
     */
    public function index(): View
    {
        ContactMessage::ensureTableExists();
        SiteSetting::ensureContactColumnsExist();

        $siteSetting = SiteSetting::firstOrCreate(['id' => 1]);
        $unreadCount = Schema::hasTable('contact_messages')
            ? ContactMessage::query()->unread()->count()
            : 0;

        $tableMissing = ! Schema::hasTable('contact_messages') || ! Schema::hasColumn('site_settings', 'contact_eyebrow');

        return view('admin.contact.index', [
            'siteSetting' => $siteSetting,
            'unreadCount' => $unreadCount,
            'tableMissing' => $tableMissing,
        ]);
    }

    /**
     * Update contact page texts and settings.
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'contact_eyebrow' => ['nullable', 'string', 'max:255'],
            'contact_title' => ['required', 'string', 'max:255'],
            'contact_subtitle' => ['nullable', 'string', 'max:1000'],
            'contact_eyebrow_en' => ['nullable', 'string', 'max:255'],
            'contact_title_en' => ['nullable', 'string', 'max:255'],
            'contact_subtitle_en' => ['nullable', 'string', 'max:1000'],
            'contact_concierge_hours' => ['nullable', 'string', 'max:255'],
            'contact_concierge_hours_en' => ['nullable', 'string', 'max:255'],
            'contact_phone_concierge' => ['nullable', 'string', 'max:100'],
            'contact_email_reservations' => ['nullable', 'string', 'max:150'],
            'location_map_embed_url' => ['nullable', 'string', 'max:2000'],
            'location_coordinates' => ['nullable', 'string', 'max:255'],
        ]);

        SiteSetting::ensureContactColumnsExist();
        $siteSetting = SiteSetting::firstOrCreate(['id' => 1]);

        $existingColumns = Schema::getColumnListing('site_settings');
        $dataToUpdate = array_intersect_key($validated, array_flip($existingColumns));

        $siteSetting->update($dataToUpdate);

        return redirect()->route('admin.contact.index')
            ->with('success', 'İletişim sayfası bilgileri ve metinleri başarıyla güncellendi.');
    }

    /**
     * Toggle read/unread state of a contact message.
     */
    public function toggleRead(ContactMessage $message): RedirectResponse
    {
        $message->update(['is_read' => ! $message->is_read]);

        $statusText = $message->is_read ? 'okundu' : 'okunmadı';

        return redirect()->route('admin.contact.index')
            ->with('success', "Mesaj durumu '{$statusText}' olarak güncellendi.");
    }

    /**
     * Delete a contact message.
     */
    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.contact.index')
            ->with('success', 'İletişim mesajı başarıyla silindi.');
    }
}
