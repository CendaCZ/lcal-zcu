<?php

namespace App\Http\Controllers;

use App\Models\EventItem;

class SavedEventController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $events = EventItem::with('savedEvents')->whereHas('savedEvents', function ($q) {
            $q->where('user_id', auth()->id());
        })->get();

        return view('events.savedEvents', compact('events'));
    }
}