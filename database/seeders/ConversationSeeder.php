<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Conversation;
use App\Models\ConversationMember;

class ConversationSeeder extends Seeder
{
    public function run(): void
    {
        $conversations = [
            [
                'type' => 'channel',
                'name' => 'Community',
                'description' => 'Church-wide announcements',
                'is_private' => false,
            ],

            [
                'type' => 'group',
                'name' => 'Youth Leaders',
                'description' => 'Youth ministry leaders',
                'is_private' => true,
            ],

            [
                'type' => 'group',
                'name' => 'Worship Team',
                'description' => 'Worship ministry members',
                'is_private' => true,
            ],

            [
                'type' => 'group',
                'name' => 'Media Team',
                'description' => 'Media and production team',
                'is_private' => true,
            ],

            [
                'type' => 'group',
                'name' => 'Finance Team',
                'description' => 'Finance ministry',
                'is_private' => true,
            ],

            [
                'type' => 'group',
                'name' => 'Prayer Ministry',
                'description' => 'Prayer ministry members',
                'is_private' => true,
            ],
        ];

        foreach ($conversations as $conversation) {
            Conversation::firstOrCreate(
                [
                    'type' => $conversation['type'],
                    'name' => $conversation['name'],
                ],
                [
                    'description' => $conversation['description'],
                    'created_by' => 1,
                    'is_private' => $conversation['is_private'],
                ]
            );
        }

        $conversation = Conversation::first();

        ConversationMember::firstOrCreate([
            'conversation_id' => $conversation->id,
            'user_id' => 1,
        ], [
            'role' => 'owner',
            'joined_at' => now(),
        ]);
    }
}
