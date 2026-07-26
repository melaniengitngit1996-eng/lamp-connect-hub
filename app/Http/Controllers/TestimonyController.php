<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Testimony;
use Illuminate\Http\Request;

class TestimonyController extends Controller
{
    public function latest()
    {
        return Testimony::query()
            ->with('user:id,name')
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
                'created_at' => $testimony->created_at?->diffForHumans(),
            ]);
    }

    public function index(Request $request)
    {
        return Testimony::query()
            ->with('user:id,name')
            ->latest()
            ->get()
            ->map(function ($testimony) {
                return [
                    'id' => $testimony->id,

                    'title' => $testimony->title,

                    'content' => $testimony->content,
                    'link' => $testimony->link,

                    'author' => [
                        'id' => $testimony->user->id,
                        'name' => $testimony->user->name,
                    ],
                    'is_featured' => $testimony->is_featured,

                    'created_at' => $testimony->created_at,
                ];
            });
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'is_featured' => ['boolean'],
        ]);

        $validated['user_id'] = $request->user()->id;

        $testimony = Testimony::create($validated);

        return response()->json([
            'message' => 'Testimony created successfully.',
            'testimony' => $testimony,
        ], 201);
    }

    public function update(Request $request, Testimony $testimony)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'is_featured' => ['boolean'],
        ]);

        $testimony->update($validated);

        return response()->json([
            'message' => 'Testimony updated successfully.',
            'testimony' => $testimony,
        ]);
    }

    public function destroy(Testimony $testimony)
    {
        $testimony->delete();

        return response()->json([
            'message' => 'Testimony deleted successfully.',
        ]);
    }
}
