<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\UserResource;
use App\Mail\NewChatMessageMail;
use App\Models\Conversation;
use App\Models\File;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewChatMessage;
use Illuminate\Support\Facades\Mail;

class ChatController extends Controller
{
    private function addUnreadCounts($conversations, $user)
    {
        foreach ($conversations as $conversation) {
            $member = $conversation->members
                ->firstWhere('id', $user->id);

            $lastReadMessageId = $member?->pivot?->last_read_message_id;

            $conversation->unread_count = $conversation->messages()
                ->where('id', '>', $lastReadMessageId ?? 0)
                ->where('sender_id', '!=', $user->id)
                ->count();
        }

        return $conversations;
    }

    public function markAsRead(Conversation $conversation)
    {
        $member = $conversation->members()
            ->where('users.id', auth()->id())
            ->firstOrFail();

        $lastMessage = $conversation->messages()
            ->latest('id')
            ->first();

        $conversation->members()->updateExistingPivot(
            auth()->id(),
            [
                'last_read_message_id' => $lastMessage?->id,
            ]
        );

        return response()->json([
            'message' => 'Conversation marked as read.',
        ]);
    }

    public function index()
    {
        $user = auth()->user();

        $channels = Conversation::query()
            ->channels()
            ->visibleTo($user)
            ->with([
                'latestMessage.sender',
                'members',
            ])
            ->ordered()
            ->get();

        $groups = Conversation::query()
            ->groups()
            ->visibleTo($user)
            ->with([
                'latestMessage.sender',
                'members',
            ])
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

        $this->addUnreadCounts($channels, $user);
        $this->addUnreadCounts($groups, $user);
        $this->addUnreadCounts($directMessages, $user);

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
        $maxUploadSize = (int) setting('chat.max_upload_size', 50);

        $request->validate([
            'message' => ['nullable', 'string', 'max:5000'],
            'file' => ['nullable', 'file', 'max:' . ($maxUploadSize * 1024)],
        ]);

        abort_unless(
            $conversation->members()
                ->where('users.id', auth()->id())
                ->exists(),
            403
        );

        abort_if(
            !$request->filled('message') && !$request->hasFile('file'),
            422,
            'Message or attachment is required.'
        );

        $file = null;

        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');

            $path = $uploadedFile->store('chat-attachments', 'public');

            $file = File::create([
                'name' => pathinfo(
                    $uploadedFile->getClientOriginalName(),
                    PATHINFO_FILENAME
                ),
                'original_name' => $uploadedFile->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $uploadedFile->getMimeType(),
                'size' => $uploadedFile->getSize(),
                'disk' => 'public',
                'uploaded_by' => auth()->id(),
                'is_chat_attachment' => true,
            ]);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'type' => $request->hasFile('file') ? 'file' : 'text',
            'message' => $request->input('message') ?? '',
            'file_id' => $file?->id,
        ]);

        $conversation->touch();

        $message->load([
            'sender',
            'conversation',
            'file',
        ]);

        // Email notification
        $recipients = $conversation->members()
            ->where('users.id', '!=', auth()->id())
            ->where('email_chat_notifications', true)
            ->get();

        foreach ($recipients as $recipient) {
            Mail::to($recipient->email)
                ->send(new NewChatMessageMail($message));
        }

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

    public function updateName(
        Request $request,
        Conversation $conversation
    ) {
        abort_unless(
            $conversation->type === 'group',
            404
        );

        abort_unless(
            $conversation->created_by === auth()->id(),
            403
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],
        ]);

        $conversation->update([
            'name' => $validated['name'],
        ]);

        return new ConversationResource(
            $conversation->fresh()
        );
    }
}
