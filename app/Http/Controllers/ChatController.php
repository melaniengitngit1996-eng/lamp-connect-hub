<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\UserResource;
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
            'members' => UserResource::collection($conversation->members),
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
        $conversation = Conversation::create([
            'type' => 'group',
            'name' => $request->name ?: 'New Group',
            'created_by' => auth()->id(),
            'is_private' => true,
        ]);

        // creator
        $conversation->members()->attach(auth()->id(), [
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        // selected users
        foreach ($request->members as $userId) {

            $conversation->members()->attach($userId, [
                'role' => 'member',
                'joined_at' => now(),
            ]);
        }

        return new ConversationResource(
            $conversation->load([
                'members',
                'latestMessage.sender',
            ])->loadCount('members')
        );
    }

    public function storeDirect(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $authUser = auth()->user();
        $otherUserId = (int) $request->user_id;

        if ($authUser->id === $otherUserId) {
            return response()->json([
                'message' => 'You cannot start a conversation with yourself.',
            ], 422);
        }

        // Look for an existing direct conversation between the two users
        $conversation = Conversation::query()
            ->direct()
            ->whereHas('members', function ($query) use ($authUser) {
                $query->where('users.id', $authUser->id);
            })
            ->whereHas('members', function ($query) use ($otherUserId) {
                $query->where('users.id', $otherUserId);
            })
            ->withCount('members')
            ->having('members_count', 2)
            ->first();

        if (! $conversation) {
            $conversation = Conversation::create([
                'type' => 'direct',
                'created_by' => $authUser->id,
                'is_private' => true,
            ]);

            $conversation->members()->attach($authUser->id, [
                'role' => 'owner',
                'joined_at' => now(),
            ]);

            $conversation->members()->attach($otherUserId, [
                'role' => 'member',
                'joined_at' => now(),
            ]);

            $conversation->loadCount('members');
        }

        $conversation->load([
            'members',
            'latestMessage.sender',
        ]);

        return new ConversationResource($conversation);
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

    public function getUsers()
    {
        return User::query()
            ->whereKeyNot(auth()->id())
            ->where('status', 'approved')
            ->orderBy('name')
            ->get();
    }

    public function availableUsers(Request $request, Conversation $conversation)
    {
        abort_unless(
            $conversation->members()
                ->where('users.id', auth()->id())
                ->exists(),
            403
        );

        $memberIds = $conversation->members()
            ->pluck('users.id');

        return UserResource::collection(
            User::query()
                ->where('status', 'approved')
                ->whereKeyNot(auth()->id())
                ->whereNotIn('id', $memberIds)
                ->when($request->filled('search'), function ($query) use ($request) {
                    $query->where(function ($query) use ($request) {
                        $query->where('name', 'like', "%{$request->search}%")
                            ->orWhere('email', 'like', "%{$request->search}%");
                    });
                })
                ->orderBy('name')
                ->limit(10)
                ->get()
        );
    }

    public function destroyMessage(Message $message)
    {
        //
    }

    public function addMember(Request $request, Conversation $conversation)
    {
        abort_unless(
            $conversation->members()
                ->where('users.id', auth()->id())
                ->wherePivot('role', 'owner')
                ->exists(),
            403
        );

        $request->validate([
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['exists:users,id'],
        ]);

        foreach ($request->user_ids as $userId) {
            $conversation->members()->syncWithoutDetaching([
                $userId => [
                    'role' => 'member',
                    'joined_at' => now(),
                ],
            ]);
        }

        return response()->json([
            'message' => 'Members added successfully.',
        ]);
    }

    public function removeMember(Conversation $conversation, User $user)
    {
        abort_unless(
            $conversation->members()
                ->where('users.id', auth()->id())
                ->wherePivot('role', 'owner')
                ->exists(),
            403
        );

        if (
            $conversation->members()
            ->where('users.id', $user->id)
            ->wherePivot('role', 'owner')
            ->exists()
        ) {
            return response()->json([
                'message' => 'Owner cannot be removed.',
            ], 422);
        }

        $conversation->members()->detach($user->id);

        return response()->noContent();
    }
}
