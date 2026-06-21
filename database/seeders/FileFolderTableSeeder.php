<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FileFolderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('file_folders')->insert([
            [
                'id' => 1,
                'parent_id' => null,
                'name' => 'Documents',
                'description' => 'Main documents folder',
                'owner_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'parent_id' => 1,
                'name' => 'Contracts',
                'description' => 'Contract files',
                'owner_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'id' => 3,
                'parent_id' => 1,
                'name' => 'Reports',
                'description' => 'Monthly reports',
                'owner_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'id' => 4,
                'parent_id' => null,
                'name' => 'Images',
                'description' => 'Image storage',
                'owner_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'id' => 5,
                'parent_id' => 4,
                'name' => 'Events',
                'description' => 'Event photos',
                'owner_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
        ]);
    }
}
