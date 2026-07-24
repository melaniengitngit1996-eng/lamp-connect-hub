<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    public function index(Post $post)
    {
        return $post->comments()
            ->with('user:id,name')
            ->latest()
            ->get()
            ->map(fn($comment) => [
                'id' => $comment->id,
                'author' => $comment->user->name,
                'initials' => collect(explode(' ', $comment->user->name))
                    ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                    ->take(2)
                    ->implode(''),
                'content' => $comment->content,
                'created_at' => $comment->created_at->diffForHumans(),
                'can_delete' => auth()->id() === $comment->user_id,
            ]);
    }

    public function store(Post $post, Request $request)
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $post->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $validated['content'],
        ]);

        return response()->json([
            'message' => 'Comment added.',
        ]);
    }

    public function destroy(PostComment $comment, Request $request)
    {
        abort_unless($comment->user_id === $request->user()->id, 403);

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted.',
        ]);
    }
}
