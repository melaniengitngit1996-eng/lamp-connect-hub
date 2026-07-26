<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Composition;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class CompositionController extends Controller
{
    public function latest()
    {
        return Composition::query()
            ->with('user:id,name')
            ->where('status', 'published')
            ->latest('published_at')
            ->take(5)
            ->get()
            ->map(fn($composition) => [
                'id' => $composition->id,
                'title' => $composition->title,
                'description' => $composition->description,
                'type' => $composition->type,
                'user' => $composition->user->name,
                'published_at' => $composition->published_at?->diffForHumans(),
                'download_url' => Storage::url($composition->file_path),
                'downloads' => $composition->downloads,
            ]);
    }

    public function index()
    {
        return Composition::query()
            ->with('user:id,name')
            ->latest('published_at')
            ->get()
            ->map(fn($composition) => [
                'id' => $composition->id,
                'title' => $composition->title,
                'description' => $composition->description,
                'type' => $composition->type,
                'file_name' => $composition->file_name,
                'file_size' => $composition->file_size_formatted,
                'downloads' => $composition->downloads,
                'status' => $composition->status,
                'is_featured' => $composition->is_featured,
                'published_at' => $composition->published_at,
                'published_at_formatted' => $composition->published_at?->diffForHumans(),
                'author' => $composition->user->name,
            ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:song,setlist,chord_chart,lead_sheet,lyrics,sheet_music,audio,backing_track',
            'file' => 'required|file|max:51200', // 50MB
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $file = $request->file('file');

        $path = $file->store('compositions', 'public');

        $composition = Composition::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'status' => $validated['status'],
            'is_featured' => $validated['is_featured'] ?? false,
            'published_at' => $validated['published_at'] ?? null,
        ]);

        return response()->json($composition, 201);
    }

    public function update(Request $request, Composition $composition)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:song,setlist,chord_chart,lead_sheet,lyrics,sheet_music,audio,backing_track',
            'file' => 'nullable|file|max:51200',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('file')) {

            if (
                $composition->file_path &&
                Storage::disk('public')->exists($composition->file_path)
            ) {
                Storage::disk('public')->delete($composition->file_path);
            }

            $file = $request->file('file');

            $path = $file->store('compositions', 'public');

            $composition->file_path = $path;
            $composition->file_name = $file->getClientOriginalName();
            $composition->mime_type = $file->getMimeType();
            $composition->file_size = $file->getSize();
        }

        $composition->title = $validated['title'];
        $composition->description = $validated['description'];
        $composition->type = $validated['type'];
        $composition->status = $validated['status'];
        $composition->is_featured = $validated['is_featured'] ?? false;
        $composition->published_at = $validated['published_at'] ?? null;

        $composition->save();

        return response()->json($composition);
    }

    public function destroy(Composition $composition)
    {
        if (
            $composition->file_path &&
            Storage::disk('public')->exists($composition->file_path)
        ) {
            Storage::disk('public')->delete($composition->file_path);
        }

        $composition->delete();

        return response()->json([
            'message' => 'Composition deleted successfully.',
        ]);
    }
}
