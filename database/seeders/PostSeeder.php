<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (! $user) {
            $this->command->warn('No users found. Skipping PostSeeder.');
            return;
        }

        $posts = [
            [
                'content' => 'Just wanted to say how blessed I am by this community. No link, just gratitude ❤️',
                'link' => null,
            ],
            [
                'content' => 'Devotional thought for the week — a short read worth your morning coffee ☕',
                'link' => 'https://www.desiringgod.org/articles',
            ],
            [
                'content' => 'Reminder: Youth night this Friday at 7pm. RSVP through the link below 🎉',
                'link' => 'https://forms.gle/youth-night-rsvp',
            ],
            [
                'content' => 'Great article on small group leadership I came across this morning ✨',
                'link' => 'https://www.christianitytoday.com/pastors/small-groups',
            ],
            [
                'content' => 'Updated the slides for this Sunday\'s service. Feedback welcome!',
                'link' => 'https://docs.google.com/presentation/d/1abc123',
            ],
            [
                'content' => 'Found this beautiful worship song for next week\'s set. What do you think team?',
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            ],
        ];

        foreach ($posts as $index => $post) {
            Post::updateOrCreate(
                [
                    'content' => $post['content'],
                ],
                [
                    'user_id' => $user->id,
                    'link' => $post['link'],
                    'status' => 'published',
                    'created_at' => now()->subDays($index),
                    'updated_at' => now()->subDays($index),
                ]
            );
        }
    }
}
