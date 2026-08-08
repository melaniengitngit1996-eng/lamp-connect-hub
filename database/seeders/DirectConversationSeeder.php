<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\ConversationMember;
use Illuminate\Database\Seeder;

class DirectConversationSeeder extends Seeder
{
    public function run(): void
    {
        $pairs = [
            [1, 2],
            [1, 3],
            [1, 4],
        ];

        foreach ($pairs as [$userA, $userB]) {

            $conversation = Conversation::create([
                'type' => 'direct',
                'name' => null,
                'description' => null,
                'created_by' => $userA,
                'is_private' => true,
            ]);

            ConversationMember::create([
                'conversation_id' => $conversation->id,
                'user_id' => $userA,
                'role' => 'owner',
                'joined_at' => now(),
            ]);

            ConversationMember::create([
                'conversation_id' => $conversation->id,
                'user_id' => $userB,
                'role' => 'member',
                'joined_at' => now(),
            ]);
        }
    }
}
