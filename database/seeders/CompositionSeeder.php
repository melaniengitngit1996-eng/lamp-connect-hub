<?php

namespace Database\Seeders;

use App\Models\Composition;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompositionSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (! $user) {
            $this->command->warn('No users found. Skipping CompositionSeeder.');
            return;
        }

        $compositions = [
            [
                'title' => 'Lamp Unto My Feet',
                'description' => 'New worship song: "Lamp Unto My Feet" — chord chart and lead sheet available.',
                'type' => 'song',
                'file_path' => 'compositions/lamp-unto-my-feet.pdf',
                'file_name' => 'lamp-unto-my-feet.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 248576,
                'is_featured' => true,
                'status' => 'published',
                'downloads' => 132,
                'published_at' => now()->subDay(),
            ],
            [
                'title' => 'Sunday Worship Setlist',
                'description' => 'Sunday setlist for this week — download the complete worship pack with lyrics and chord charts.',
                'type' => 'setlist',
                'file_path' => 'compositions/sunday-setlist.zip',
                'file_name' => 'sunday-setlist.zip',
                'mime_type' => 'application/zip',
                'file_size' => 1542658,
                'is_featured' => true,
                'status' => 'published',
                'downloads' => 89,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Great Is Your Mercy',
                'description' => 'Lead sheet for "Great Is Your Mercy" is now available for all worship teams.',
                'type' => 'lead_sheet',
                'file_path' => 'compositions/great-is-your-mercy.pdf',
                'file_name' => 'great-is-your-mercy.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 196432,
                'is_featured' => false,
                'status' => 'published',
                'downloads' => 54,
                'published_at' => now()->subWeek(),
            ],
            [
                'title' => 'Living Hope',
                'description' => 'Chord chart for "Living Hope" in the key of G is ready for this Sunday service.',
                'type' => 'chord_chart',
                'file_path' => 'compositions/living-hope.pdf',
                'file_name' => 'living-hope.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 184320,
                'is_featured' => false,
                'status' => 'published',
                'downloads' => 41,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Communion Worship Medley',
                'description' => 'Audio demo and backing track uploaded for this month\'s communion worship medley.',
                'type' => 'audio',
                'file_path' => 'compositions/communion-medley.mp3',
                'file_name' => 'communion-medley.mp3',
                'mime_type' => 'audio/mpeg',
                'file_size' => 5120000,
                'is_featured' => false,
                'status' => 'published',
                'downloads' => 27,
                'published_at' => now()->subDays(14),
            ],
        ];

        foreach ($compositions as $composition) {
            Composition::updateOrCreate(
                [
                    'title' => $composition['title'],
                ],
                array_merge($composition, [
                    'user_id' => $user->id,
                ])
            );
        }
    }
}
