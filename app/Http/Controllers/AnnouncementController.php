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
}
