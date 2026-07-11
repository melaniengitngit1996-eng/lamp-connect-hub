<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            'users' => [
                'view',
                'update',
                'delete',
            ],

            'roles' => [
                'view',
                'create',
                'update',
                'delete',
            ],

            'drive' => [
                'view',
                'upload',
                'download',
                'share',
                'delete',
            ],

            'events' => [
                'view',
                'create',
                'update',
                'delete',
                'publish',
            ],

            'chat' => [
                'view',
                'moderate',
            ],
        ];

        foreach ($permissions as $module => $actions) {
            foreach ($actions as $action) {
                Permission::findOrCreate(
                    "{$module}.{$action}"
                );
            }
        }
    }
}
