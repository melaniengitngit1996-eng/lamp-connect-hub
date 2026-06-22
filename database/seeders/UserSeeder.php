<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@lamp.test'],
            [
                'name' => 'LAMP Admin',
                'password' => Hash::make('password123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'member@lamp.test'],
            [
                'name' => 'LAMP Member',
                'password' => Hash::make('password123'),
            ]
        );
    }
}
