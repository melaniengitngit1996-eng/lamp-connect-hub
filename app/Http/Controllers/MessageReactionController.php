<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MessageReaction;
use Illuminate\Http\Request;

class MessageReactionController extends Controller
{
    public function store(Request $request, Message $message)
    {
        abort_unless(
            $message->conversation
                ->members()
                ->where('users.id', auth()->id())
                ->exists(),
            403
        );

        $request->validate([
            'reaction' => ['required', 'string', 'in:like,love,haha,wow,sad,angry'],
        ]);

        $reaction = MessageReaction::updateOrCreate(
            [
                'message_id' => $message->id,
                'user_id' => auth()->id(),
            ],
            [
                'reaction' => $request->reaction,
            ]
        );

        $reaction->load('user');

        return response()->json([
            'reaction' => [
                'id' => $reaction->id,
                'user_id' => $reaction->user_id,
                'reaction' => $reaction->reaction,
                'user' => [
                    'id' => $reaction->user->id,
                    'name' => $reaction->user->name,
                    'initials' => $reaction->user->initials,
                ],
            ],
        ]);
    }

    public function destroy(Message $message)
    {
        abort_unless(
            $message->conversation
                ->members()
                ->where('users.id', auth()->id())
                ->exists(),
            403
        );

        MessageReaction::where('message_id', $message->id)
            ->where('user_id', auth()->id())
            ->delete();

        return response()->json([
            'message' => 'Reaction removed.',
        ]);
    }
}
