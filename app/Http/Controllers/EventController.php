<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\SiteSetting;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Display the specified event detail page.
     */
    public function show(Event $event): View
    {
        abort_unless($event->is_active, 404);

        $otherEvents = Event::query()
            ->active()
            ->where('id', '!=', $event->id)
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        return view('events.show', [
            'event' => $event,
            'otherEvents' => $otherEvents,
            'siteSetting' => SiteSetting::query()->first(),
        ]);
    }
}
