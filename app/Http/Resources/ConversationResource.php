<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $otherUser = null;

        if ($this->type === 'direct') {
            $otherUser = $this->members
                ->firstWhere('id', '!=', auth()->id());
        }

        return [
            'id' => $this->id,
            'type' => $this->type,

            'name' => $this->type === 'direct'
                ? $otherUser?->name
                : $this->name,

            'avatar' => $this->type === 'direct'
                ? $otherUser?->avatar
                : null,

            'initials' => $this->type === 'direct'
                ? $otherUser?->initials
                : null,

            'latest_message' => new MessageResource(
                $this->whenLoaded('latestMessage')
            ),

            'members_count' => $this->members_count,
            'is_owner' => $this->members()
                ->where('users.id', auth()->id())
                ->wherePivot('role', 'owner')
                ->exists(),
            'unread_count' => $this->unread_count ?? 0,
        ];
    }
}
