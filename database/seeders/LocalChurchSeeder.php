<?php

namespace Database\Seeders;

use App\Models\LocalChurch;
use Illuminate\Database\Seeder;

class LocalChurchSeeder extends Seeder
{
    public function run(): void
    {
        LocalChurch::insert([
            [
                'name' => 'Muntinlupa',
                'code' => 'LPMU',
            ],
            [
                'name' => 'Binan',
                'code' => 'LPBI',
            ],
            [
                'name' => 'Canlubang',
                'code' => 'LPCA',
            ],
        ]);
    }
}
