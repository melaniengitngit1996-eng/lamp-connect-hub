<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Testimony;

class TestimonyController extends Controller
{
    public function latest()
    {
        return Testimony::query()
            ->with('user:id,name')
            ->approved()
            ->latest('approved_at')
            ->take(5)
            ->get()
            ->map(fn($testimony) => [
                'id' => $testimony->id,
                'title' => $testimony->title,
                'content' => $testimony->content,
                'author' => $testimony->user->name,
                'initials' => collect(explode(' ', $testimony->user->name))
                    ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                    ->take(2)
                    ->implode(''),
                'approved_at' => $testimony->approved_at?->diffForHumans(),
            ]);
    }
}
