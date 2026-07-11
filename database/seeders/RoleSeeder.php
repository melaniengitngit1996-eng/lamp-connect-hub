<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrator',
                'description' => 'Full access to all modules and system settings.',
                'is_system' => true,
            ],
            [
                'name' => 'Pastor',
                'description' => 'Manage church-wide activities, members, and events.',
                'is_system' => true,
            ],
            [
                'name' => 'Cluster Leader',
                'description' => 'Manage assigned clusters and their members.',
                'is_system' => true,
            ],
            [
                'name' => 'Ministry Leader',
                'description' => 'Manage ministry members and activities.',
                'is_system' => true,
            ],
            [
                'name' => 'Member',
                'description' => 'Standard portal access for registered members.',
                'is_system' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
