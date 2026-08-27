<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $createdAt = $this->created_at?->timezone('Asia/Manila');

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
            'date_key' => $createdAt?->format('Y-m-d'),
            'created_at_formatted' => $createdAt?->format('g:i A'),

            'date_label' => $createdAt?->isToday()
                ? 'Today'
                : ($createdAt?->isYesterday()
                    ? 'Yesterday'
                    : $createdAt?->format(
                        $createdAt->year === now('Asia/Manila')->year
                            ? 'M d'
                            : 'M d, Y'
                    )),

            'updated_at' => $this->updated_at,
            'file' => $this->whenLoaded('file', function () {
                if (!$this->file) {
                    return null;
                }

                return [
                    'id' => $this->file->id,
                    'name' => $this->file->original_name,
                    'mime_type' => $this->file->mime_type,
                    'size' => $this->file->size,
                    'url' => Storage::disk($this->file->disk)->url($this->file->path),
                ];
            }),
        ];
    }
}
