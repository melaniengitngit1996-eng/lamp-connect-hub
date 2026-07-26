<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title' => 'AWTA 2025',
                'description' => 'Join us for three days of worship, powerful preaching, fellowship, and spiritual renewal with the whole LAMP family.',
                'cover_image' => 'images/event-awta.jpg',
                'starts_at' => now()->addDays(30),
                'ends_at' => now()->addDays(33),
                'venue' => 'LAMP Convention Center',
                'status' => 'published',
            ],
            [
                'title' => 'Camping 2025',
                'description' => 'A family camping experience filled with outdoor activities, worship, bonfire nights, and meaningful fellowship.',
                'cover_image' => 'images/event-camping.jpg',
                'starts_at' => now()->addDays(60),
                'ends_at' => now()->addDays(62),
                'venue' => 'Camp Riverside',
                'status' => 'published',
            ],
            [
                'title' => 'Youth Revival Night',
                'description' => 'An evening of worship, testimonies, games, and a life-changing message for the youth.',
                'cover_image' => 'images/event-youth.jpg',
                'starts_at' => now()->addDays(14),
                'ends_at' => now()->addDays(14)->addHours(4),
                'venue' => 'LAMP Main Sanctuary',
                'status' => 'published',
            ],
        ];

        foreach ($events as $event) {
            Event::updateOrCreate(
                [
                    'slug' => Str::slug($event['title']),
                ],
                array_merge($event, [
                    'slug' => Str::slug($event['title']),
                ])
            );
        }
    }
}
