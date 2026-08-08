<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $channels = Conversation::query()
            ->channels()
            ->visibleTo($user)
            ->with('latestMessage.sender')
            ->ordered()
            ->get();

        $groups = Conversation::query()
            ->groups()
            ->visibleTo($user)
            ->with('latestMessage.sender')
            ->ordered()
            ->get();

        $directMessages = Conversation::query()
            ->direct()
            ->visibleTo($user)
            ->with([
                'latestMessage.sender',
                'members',
            ])
            ->ordered()
            ->get();

        return response()->json([
            'channels' => ConversationResource::collection($channels),
            'groups' => ConversationResource::collection($groups),
            'direct_messages' => ConversationResource::collection($directMessages),
        ]);
    }

    public function show(Conversation $conversation)
    {
        abort_unless(
            $conversation->members()
                ->where('users.id', auth()->id())
                ->exists(),
            403
        );

        $conversation->loadCount('members');

        $conversation->load([
            'members',
            'messages.sender',
            'messages.reply.sender',
            'messages.file',
        ]);

        return response()->json([
            'conversation' => new ConversationResource($conversation),
            'messages' => MessageResource::collection(
                $conversation->messages()
                    ->with([
                        'sender',
                        'reply.sender',
                        'file',
                    ])
                    ->oldest()
                    ->get()
            ),
        ]);
    }

    public function storeGroup(Request $request)
    {
        //
    }

    public function storeDirect(Request $request)
    {
        //
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        abort_unless(
            $conversation->members()
                ->where('users.id', auth()->id())
                ->exists(),
            403
        );

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'type' => 'text',
            'message' => $request->message,
        ]);

        $conversation->touch();

        $message->load('sender');

        return new MessageResource($message);
    }

    public function destroyMessage(Message $message)
    {
        //
    }

    public function addMember(Request $request, Conversation $conversation)
    {
        //
    }

    public function removeMember(Conversation $conversation, User $user)
    {
        //
    }
}
