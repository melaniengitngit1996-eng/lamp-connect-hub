<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function highlights()
    {
        return Event::query()
            ->where('status', 'published')
            ->where('is_featured', true)
            ->latest('starts_at')
            ->take(5)
            ->get()
            ->map(fn($event) => [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'image' => $event->cover_image,
                'published_at' => $event->starts_at->diffForHumans(),
            ]);
    }
}
