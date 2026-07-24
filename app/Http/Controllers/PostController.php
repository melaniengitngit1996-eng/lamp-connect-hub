<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        return Post::query()
            ->with('user:id,name')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'author' => $post->user->name,
                    'initials' => collect(explode(' ', $post->user->name))
                        ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                        ->take(2)
                        ->implode(''),
                    'content' => $post->content,
                    'link' => $post->link,
                    'likes' => $post->likes()->count(),

                    'liked' => Auth::check()
                        ? $post->likes->contains('user_id', Auth::id())
                        : false,
                    'comments' => $post->comments()->count(),
                    'created_at' => $post->created_at->format('j/n/Y'),
                    'can_delete' => Auth::check()
                        ? Auth::id() === $post->user_id
                        : false,
                ];
            });
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:5000'],
            'link' => ['nullable', 'url', 'max:2048'],
        ]);

        $post = Post::create([
            'user_id' => $request->user()->id,
            'content' => $validated['content'],
            'link' => $validated['link'] ?? null,
            'status' => 'published',
        ]);

        return response()->json($post, 201);
    }

    public function destroy(Post $post, Request $request)
    {
        abort_unless($post->user_id === $request->user()->id, 403);

        $post->delete();

        return response()->json([
            'message' => 'Post deleted.',
        ]);
    }

    public function toggleLike(Post $post, Request $request)
    {
        $like = $post->likes()
            ->where('user_id', $request->user()->id)
            ->first();

        if ($like) {
            $like->delete();
        } else {
            $post->likes()->create([
                'user_id' => $request->user()->id,
            ]);
        }

        return response()->json([
            'likes' => $post->likes()->count(),
            'liked' => ! $like,
        ]);
    }
}
