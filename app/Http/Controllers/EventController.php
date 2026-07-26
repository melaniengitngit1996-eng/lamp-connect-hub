<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function highlights()
    {
        return Event::query()
            ->where('status', 'published')
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

    public function index()
    {
        return Event::query()
            ->latest('starts_at')
            ->get()
            ->map(fn($event) => [
                'id' => $event->id,
                'title' => $event->title,
                'slug' => $event->slug,
                'description' => $event->description,
                'cover_image' => $event->cover_image,
                'starts_at' => $event->starts_at,
                'ends_at' => $event->ends_at,

                'starts_at_formatted' => $event->starts_at_formatted,
                'ends_at_formatted' => $event->ends_at_formatted,
                'date_range' => $event->date_range,

                'venue' => $event->venue,
                'status' => $event->status
            ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'venue' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,cancelled,archived',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request
                ->file('cover_image')
                ->store('events', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']);

        if (
            $validated['status'] === 'published' &&
            empty($validated['published_at'])
        ) {
            $validated['published_at'] = now();
        }

        $event = Event::create($validated);

        return response()->json($event, 201);
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'venue' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,cancelled,archived',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('cover_image')) {

            if (
                $event->cover_image &&
                Storage::disk('public')->exists($event->cover_image)
            ) {
                Storage::disk('public')->delete($event->cover_image);
            }

            $validated['cover_image'] = $request
                ->file('cover_image')
                ->store('events', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']);

        if (
            $event->status !== 'published' &&
            $validated['status'] === 'published'
        ) {
            $validated['published_at'] = now();
        }

        $event->update($validated);

        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        if (
            $event->cover_image &&
            Storage::disk('public')->exists($event->cover_image)
        ) {
            Storage::disk('public')->delete($event->cover_image);
        }

        $event->delete();

        return response()->json([
            'message' => 'Event deleted successfully.',
        ]);
    }
}
