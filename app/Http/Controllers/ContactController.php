<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display the public contact page.
     */
    public function index(): View
    {
        $siteSetting = SiteSetting::query()->first();

        return view('contact', compact('siteSetting'));
    }

    /**
     * Store a new contact form submission.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $validated['ip_address'] = $request->ip();

        ContactMessage::ensureTableExists();

        if (Schema::hasTable('contact_messages')) {
            ContactMessage::create($validated);
        }

        $message = app()->isLocale('en')
            ? 'Thank you for contacting us. Our Peninsula concierge team will be in touch with you shortly.'
            : 'Mesajınız bize başarıyla ulaştı. Özel konsiyerj ekibimiz en kısa sürede sizinle iletişime geçecektir.';

        return back()->with('contact_success', $message);
    }
}
