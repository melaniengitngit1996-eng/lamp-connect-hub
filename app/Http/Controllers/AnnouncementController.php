<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function latest()
    {
        return Announcement::query()
            ->with('user:id,name')
            ->where('status', 'published')
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->take(5)
            ->get()
            ->map(fn($announcement) => [
                'id' => $announcement->id,
                'title' => $announcement->title,
                'content' => $announcement->content,
                'author' => $announcement->user->name,
                'published_at' => $announcement->published_at?->diffForHumans(),
                'is_pinned' => $announcement->is_pinned,
            ]);
    }

    public function index()
    {
        return Announcement::query()
            ->with('user:id,name')
            ->latest('published_at')
            ->get()
            ->map(fn($announcement) => [
                'id' => $announcement->id,
                'title' => $announcement->title,
                'content' => $announcement->content,
                'status' => $announcement->status,
                'is_pinned' => $announcement->is_pinned,
                'published_at' => $announcement->published_at,
                'published_at_formatted' => $announcement->published_at_formatted,
                'author' => $announcement->user->name,
            ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published,cancelled,archived',
            'is_pinned' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $announcement = Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $validated['status'],
            'is_pinned' => $validated['is_pinned'] ?? false,
            'published_at' => $validated['published_at'] ?? null,
            'user_id' => auth()->id(),
        ]);

        return response()->json($announcement, 201);
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published,cancelled,archived',
            'is_pinned' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $announcement->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $validated['status'],
            'is_pinned' => $validated['is_pinned'] ?? false,
            'published_at' => $validated['published_at'] ?? null,
        ]);

        return response()->json($announcement);
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return response()->json([
            'message' => 'Announcement deleted successfully.',
        ]);
    }
}
