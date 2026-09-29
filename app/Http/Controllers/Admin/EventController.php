<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index(): View
    {
        return view('admin.events.index', [
            'events' => Event::query()->orderBy('sort_order')->orderBy('id')->get(),
            'siteSetting' => SiteSetting::firstOrCreate(['id' => 1]),
        ]);
    }

    /**
     * Show the form for creating a new event.
     */
    public function create(): View
    {
        return view('admin.events.create', [
            'nextSortOrder' => ((int) Event::max('sort_order')) + 1,
        ]);
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'image' => ['nullable', 'image', 'max:5120'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'category' => ['nullable', 'string', 'max:100'],
            'category_en' => ['nullable', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'description_en' => ['nullable', 'string', 'max:2000'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_text_en' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:500'],
            'show_on_home' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
        ]);

        $imagePath = $validated['image_url'] ?? null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
        }

        Event::create([
            'image_path' => $imagePath,
            'category' => $validated['category'] ?? null,
            'category_en' => $validated['category_en'] ?? null,
            'title' => $validated['title'],
            'title_en' => $validated['title_en'] ?? null,
            'description' => $validated['description'] ?? null,
            'description_en' => $validated['description_en'] ?? null,
            'button_text' => $validated['button_text'] ?: 'Deneyimi Rezerve Et',
            'button_text_en' => $validated['button_text_en'] ?: 'Reserve Journey',
            'button_url' => $validated['button_url'] ?: '#',
            'show_on_home' => ! empty($validated['show_on_home']),
            'is_active' => ! empty($validated['is_active']),
            'sort_order' => $validated['sort_order'],
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Etkinlik başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event): View
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'image' => ['nullable', 'image', 'max:5120'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'category' => ['nullable', 'string', 'max:100'],
            'category_en' => ['nullable', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'description_en' => ['nullable', 'string', 'max:2000'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_text_en' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:500'],
            'show_on_home' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
        ]);

        $imagePath = $event->image_path;

        if ($request->hasFile('image')) {
            if ($event->image_path && ! str_starts_with($event->image_path, 'http')) {
                Storage::disk('public')->delete($event->image_path);
            }
            $imagePath = $request->file('image')->store('events', 'public');
        } elseif (! empty($validated['image_url'])) {
            $imagePath = $validated['image_url'];
        }

        $event->update([
            'image_path' => $imagePath,
            'category' => $validated['category'] ?? null,
            'category_en' => $validated['category_en'] ?? null,
            'title' => $validated['title'],
            'title_en' => $validated['title_en'] ?? null,
            'description' => $validated['description'] ?? null,
            'description_en' => $validated['description_en'] ?? null,
            'button_text' => $validated['button_text'] ?: 'Deneyimi Rezerve Et',
            'button_text_en' => $validated['button_text_en'] ?: 'Reserve Journey',
            'button_url' => $validated['button_url'] ?: '#',
            'show_on_home' => ! empty($validated['show_on_home']),
            'is_active' => ! empty($validated['is_active']),
            'sort_order' => $validated['sort_order'],
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Etkinlik başarıyla güncellendi.');
    }

    /**
     * Toggle show on home status quickly.
     */
    public function toggleHome(Event $event): RedirectResponse
    {
        $event->update([
            'show_on_home' => ! $event->show_on_home,
        ]);

        $status = $event->show_on_home ? 'anasayfada yayınlandı' : 'anasayfadan kaldırıldı';

        return back()->with('success', "Etkinlik {$status}.");
    }

    /**
     * Update section header for homepage events.
     */
    public function updateSection(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'events_eyebrow' => ['nullable', 'string', 'max:255'],
            'events_title' => ['required', 'string', 'max:255'],
            'events_subtitle' => ['nullable', 'string', 'max:1000'],
            'events_eyebrow_en' => ['nullable', 'string', 'max:255'],
            'events_title_en' => ['nullable', 'string', 'max:255'],
            'events_subtitle_en' => ['nullable', 'string', 'max:1000'],
        ]);

        $siteSetting = SiteSetting::firstOrFail();
        $siteSetting->update($validated);

        return back()->with('success', 'Bölüm başlıkları başarıyla güncellendi.');
    }

    /**
     * Remove the specified event.
     */
    public function destroy(Event $event): RedirectResponse
    {
        if ($event->image_path && ! str_starts_with($event->image_path, 'http')) {
            Storage::disk('public')->delete($event->image_path);
        }

        $event->delete();

        return back()->with('success', 'Etkinlik silindi.');
    }
}
