<?php

namespace Database\Seeders;

use App\Models\Ministry;
use App\Models\LocalChurch;
use Illuminate\Database\Seeder;

class MinistrySeeder extends Seeder
{
    public function run(): void
    {
        // we will load the ministries passed from sign up
        // $church = LocalChurch::first();

        // Ministry::insert([
        //     [
        //         'local_church_id' => $church->id,
        //         'name' => 'Worship Ministry',
        //     ],
        //     [
        //         'local_church_id' => $church->id,
        //         'name' => 'Ushering Ministry',
        //     ],
        // ]);
    }
}
