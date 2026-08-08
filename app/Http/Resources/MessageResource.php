<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'conversation_id' => $this->conversation_id,

            'sender' => new UserResource(
                $this->whenLoaded('sender')
            ),

            'type' => $this->type,
            'message' => $this->message,

            'file_id' => $this->file_id,
            'reply_to' => $this->reply_to,

            'edited_at' => $this->edited_at,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
