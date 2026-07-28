<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@lamp.test'],
            [
                'name' => 'Some Admin',
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'status' => 'approved',
            ]
        );

        $admin->syncRoles(['Administrator']);

        $member = User::updateOrCreate(
            ['email' => 'member@lamp.test'],
            [
                'name' => 'Some Member',
                'username' => 'someuser',
                'password' => Hash::make('password123'),
                'status' => 'approved',
            ]
        );

        $member->syncRoles(['Member']);
    }
}
