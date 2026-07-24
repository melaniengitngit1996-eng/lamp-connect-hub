<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (! $user) {
            $this->command->warn('No users found. Skipping AnnouncementSeeder.');
            return;
        }

        $announcements = [
            [
                'title' => 'New Sermon Series Starts This Sunday',
                'content' => 'Join us for our new 6-week sermon series, "Walking in the Spirit", every Sunday at 9:00 AM.',
                'status' => 'published',
                'is_pinned' => true,
                'published_at' => now()->subHours(9),
            ],
            [
                'title' => 'Building Fund Update',
                'content' => 'Praise God! We have reached 72% of our building fund goal this month. Thank you for your generosity and continued support.',
                'status' => 'published',
                'is_pinned' => false,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Baptism Service Registration',
                'content' => 'Our water baptism service will be held on the last Sunday of this month. Please register at the Information Desk or through the Church Connect app.',
                'status' => 'published',
                'is_pinned' => false,
                'published_at' => now()->subDays(4),
            ],
            [
                'title' => 'Prayer & Fasting Week',
                'content' => 'The church-wide Prayer and Fasting Week begins next Monday. Daily devotion guides will be available online.',
                'status' => 'published',
                'is_pinned' => false,
                'published_at' => now()->subDays(6),
            ],
            [
                'title' => 'Leaders Meeting',
                'content' => 'All ministry and cluster leaders are invited to attend the monthly leadership meeting this Saturday at 2:00 PM.',
                'status' => 'draft',
                'is_pinned' => false,
                'published_at' => null,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::updateOrCreate(
                [
                    'title' => $announcement['title'],
                ],
                array_merge($announcement, [
                    'user_id' => $user->id,
                ])
            );
        }
    }
}
