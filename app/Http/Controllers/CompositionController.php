<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Composition;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
                'type' => Str::headline(str_replace('_', ' ', $composition->type)),
                'file_name' => $composition->file_name,
                'file_size' => $composition->file_size_formatted,
                'downloads' => $composition->downloads,
                'status' => ucfirst($composition->status),
                'is_featured' => $composition->is_featured,
                'published_at' => $composition->published_at_formatted,
                'author' => $composition->user->name,
            ]);
    }
}
