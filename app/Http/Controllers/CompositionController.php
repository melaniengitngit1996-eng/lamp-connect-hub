<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Composition;
use Illuminate\Support\Facades\Storage;

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
}
