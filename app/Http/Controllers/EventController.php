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
            ->with('images')
            ->where('status', 'published')
            ->latest('starts_at')
            ->take(5)
            ->get()
            ->map(fn($event) => [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'image' => $event->cover_image,
                'cover_image_url' => $event->cover_image_url,
                'published_at' => $event->starts_at->diffForHumans(),
                'images' => $event->images->map(fn($image) => [
                    'id' => $image->id,
                    'url' => Storage::disk('public')->url($image->path),
                    'is_cover' => $image->is_cover,
                ]),
            ]);
    }

    public function index()
    {
        return Event::query()
            ->with('images')
            ->latest('starts_at')
            ->get()
            ->map(fn($event) => [
                'id' => $event->id,
                'title' => $event->title,
                'slug' => $event->slug,
                'description' => $event->description,

                'cover_image' => $event->cover_image,
                'cover_image_url' => $event->cover_image_url,

                'images' => $event->images->map(fn($image) => [
                    'id' => $image->id,
                    'path' => $image->path,
                    'url' => Storage::disk('public')->url($image->path),
                    'is_cover' => $image->is_cover,
                ]),

                'starts_at' => $event->starts_at,
                'ends_at' => $event->ends_at,

                'starts_at_formatted' => $event->starts_at_formatted,
                'ends_at_formatted' => $event->ends_at_formatted,
                'date_range' => $event->date_range,

                'venue' => $event->venue,
                'status' => $event->status,
            ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'venue' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,cancelled,archived',

            'photos' => 'required|array|max:20',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'photos.required' => 'Please upload at least one photo.',
            'photos.max' => 'You may upload a maximum of 20 photos.',
            'photos.*.image' => 'Each file must be an image.',
            'photos.*.max' => 'Each photo must not exceed 5MB.',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        // Remove photos because it is not a column in events table
        unset($validated['photos']);

        $event = Event::create($validated);

        /*
        |--------------------------------------------------------------------------
        | Store event photos
        |--------------------------------------------------------------------------
        */

        foreach ($request->file('photos', []) as $index => $photo) {
            $path = $photo->store('events', 'public');

            $event->images()->create([
                'path' => $path,
                'is_cover' => $index === 0,
            ]);
        }

        // Keep events.cover_image synced with the cover photo
        $coverImage = $event->images()
            ->where('is_cover', true)
            ->first();

        if ($coverImage) {
            $event->update([
                'cover_image' => $coverImage->path,
            ]);
        }

        return response()->json(
            $event->load('images'),
            201
        );
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'venue' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,cancelled,archived',

            'photos' => 'nullable|array|max:20',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',

            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer|exists:event_images,id',
        ], [
            'photos.max' => 'You may upload a maximum of 20 photos.',
            'photos.*.image' => 'Each file must be an image.',
            'photos.*.max' => 'Each photo must not exceed 5MB.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Delete selected existing photos
        |--------------------------------------------------------------------------
        */

        $deleteImageIds = $request->input('delete_images', []);

        if (!empty($deleteImageIds)) {
            $imagesToDelete = $event->images()
                ->whereIn('id', $deleteImageIds)
                ->get();

            foreach ($imagesToDelete as $image) {
                if (
                    $image->path &&
                    Storage::disk('public')->exists($image->path)
                ) {
                    Storage::disk('public')->delete($image->path);
                }

                $image->delete();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Add new photos
        |--------------------------------------------------------------------------
        */

        foreach ($request->file('photos', []) as $photo) {
            $path = $photo->store('events', 'public');

            $event->images()->create([
                'path' => $path,
                'is_cover' => false,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Update event information
        |--------------------------------------------------------------------------
        */

        $validated['slug'] = Str::slug($validated['title']);

        if (
            $event->status !== 'published' &&
            $validated['status'] === 'published'
        ) {
            $validated['published_at'] = now();
        }

        unset(
            $validated['photos'],
            $validated['delete_images']
        );

        $event->update($validated);

        /*
        |--------------------------------------------------------------------------
        | Make sure there is always one cover image
        |--------------------------------------------------------------------------
        */

        $images = $event->images()
            ->orderBy('id')
            ->get();

        if ($images->isNotEmpty()) {
            // Reset all covers
            $event->images()->update([
                'is_cover' => false,
            ]);

            // First remaining image becomes cover
            $coverImage = $images->first();

            $coverImage->update([
                'is_cover' => true,
            ]);

            // Keep events.cover_image synced
            $event->update([
                'cover_image' => $coverImage->path,
            ]);
        } else {
            // No images left
            $event->update([
                'cover_image' => null,
            ]);
        }

        return response()->json(
            $event->load('images'),
            200
        );
    }

    public function destroy(Event $event)
    {
        /*
        |--------------------------------------------------------------------------
        | Delete all event images
        |--------------------------------------------------------------------------
        */

        foreach ($event->images as $image) {
            if (
                $image->path &&
                Storage::disk('public')->exists($image->path)
            ) {
                Storage::disk('public')->delete($image->path);
            }

            $image->delete();
        }

        /*
        |--------------------------------------------------------------------------
        | Delete old cover_image if it exists
        |--------------------------------------------------------------------------
        */

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
