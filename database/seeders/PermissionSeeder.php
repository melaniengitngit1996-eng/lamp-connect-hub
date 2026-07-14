<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = config('permissions');

        foreach ($permissions as $module => $actions) {
            foreach ($actions as $action => $attributes) {
                Permission::updateOrCreate(
                    [
                        'name' => "{$module}.{$action}",
                        'guard_name' => 'web',
                    ],
                    [
                        'description' => $attributes['description'],
                    ]
                );
            }
        }
    }
}
