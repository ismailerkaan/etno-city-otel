<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of incoming contact inquiries/requests.
     */
    public function index(Request $request): View
    {
        ContactMessage::ensureTableExists();

        $tableMissing = ! Schema::hasTable('contact_messages');
        $filter = $request->query('status', 'all');
        $search = $request->query('q');

        if (! $tableMissing) {
            $query = ContactMessage::query()->latest();

            if ($filter === 'unread') {
                $query->where('is_read', false);
            } elseif ($filter === 'read') {
                $query->where('is_read', true);
            }

            if (! empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%");
                });
            }

            $messages = $query->paginate(15)->withQueryString();
            $unreadCount = ContactMessage::query()->unread()->count();
            $totalCount = ContactMessage::query()->count();
        } else {
            $messages = new LengthAwarePaginator([], 0, 15);
            $unreadCount = 0;
            $totalCount = 0;
        }

        return view('admin.contact-messages.index', [
            'messages' => $messages,
            'unreadCount' => $unreadCount,
            'totalCount' => $totalCount,
            'currentFilter' => $filter,
            'searchQuery' => $search,
            'tableMissing' => $tableMissing,
        ]);
    }

    /**
     * Toggle read/unread state of a contact message.
     */
    public function toggleRead(ContactMessage $message): RedirectResponse
    {
        $message->update(['is_read' => ! $message->is_read]);

        $statusText = $message->is_read ? 'okundu' : 'okunmadı';

        return redirect()->route('admin.contact-messages.index')
            ->with('success', "Talep durumu '{$statusText}' olarak güncellendi.");
    }

    /**
     * Delete a contact message.
     */
    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'İletişim talebi başarıyla silindi.');
    }
}
