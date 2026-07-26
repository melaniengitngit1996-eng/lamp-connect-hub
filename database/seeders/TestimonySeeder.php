<?php

namespace Database\Seeders;

use App\Models\Testimony;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestimonySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (! $user) {
            $this->command->warn('No users found. Skipping TestimonySeeder.');
            return;
        }

        $testimonies = [
            [
                'title' => 'God\'s Perfect Timing',
                'content' => 'God provided a new job for me after months of waiting. His timing is always perfect. Grateful to this church family for the prayers.',
                'is_featured' => true,
            ],
            [
                'title' => 'Answered Prayer',
                'content' => 'My mother\'s surgery went well and she is recovering faster than expected. Thank you for standing with us in prayer!',
                'is_featured' => true,
            ],
            [
                'title' => 'Financial Breakthrough',
                'content' => 'Financial breakthrough this week — an unexpected door opened. Truly, God sees and provides.',
                'is_featured' => true,
            ],
            [
                'title' => 'Healing',
                'content' => 'After months of treatment, my latest medical results showed significant improvement. All glory belongs to God!',
                'is_featured' => false,
            ],
            [
                'title' => 'Family Restoration',
                'content' => 'God restored peace in our family after years of misunderstanding. We continue to witness His faithfulness every day.',
                'is_featured' => false,
            ],
        ];

        foreach ($testimonies as $testimony) {
            Testimony::updateOrCreate(
                [
                    'title' => $testimony['title'],
                ],
                array_merge($testimony, [
                    'user_id' => $user->id,
                ])
            );
        }
    }
}
