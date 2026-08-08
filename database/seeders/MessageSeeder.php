<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $sampleMessages = [
            'Hello everyone! 👋',
            'Good evening!',
            'Please check the latest announcement.',
            'See you this Sunday!',
            'Thank you and God bless!',
            'Can we have a short meeting later?',
            'Noted. Thanks!',
            'I will prepare the materials.',
            'Prayer meeting starts at 7 PM.',
            'Looking forward to seeing everyone.',
        ];

        foreach (Conversation::with('members')->get() as $conversation) {

            $members = $conversation->members;

            if ($members->isEmpty()) {
                continue;
            }

            // Create 5 messages per conversation
            for ($i = 0; $i < 5; $i++) {

                if ($conversation->type === 'direct') {
                    // Alternate sender between the 2 participants
                    $sender = $members[$i % $members->count()];
                } else {
                    // Random sender for groups/channels
                    $sender = $members->random();
                }

                Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $sender->id,
                    'type' => 'text',
                    'message' => $sampleMessages[array_rand($sampleMessages)],
                ]);
            }

            // Keep conversation ordered by latest message
            $conversation->touch();
        }
    }
}
