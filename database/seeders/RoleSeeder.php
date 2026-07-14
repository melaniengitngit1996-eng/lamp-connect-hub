<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
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
                'permissions' => Permission::pluck('name')->toArray(),
            ],

            [
                'name' => 'Pastor',
                'description' => 'Manage church-wide members, events, ministries, and activities.',
                'is_system' => true,
                'permissions' => [
                    'members.view',
                    'members.approve',

                    'drive.view',
                    'drive.upload',
                    'drive.download',
                    'drive.share',
                    'drive.delete',

                    'posts.view',
                    'posts.create',
                    'posts.update',
                    'posts.delete',

                    'chat.view',
                    'chat.moderate',

                    'settings.view',
                ],
            ],

            [
                'name' => 'Cluster Leader',
                'description' => 'Manage assigned clusters and oversee their members.',
                'is_system' => true,
                'permissions' => [
                    'members.view',

                    'drive.view',
                    'drive.upload',
                    'drive.download',
                    'drive.share',

                    'posts.view',
                    'posts.create',
                    'posts.update',

                    'chat.view',
                ],
            ],

            [
                'name' => 'Ministry Leader',
                'description' => 'Manage ministry members and ministry activities.',
                'is_system' => true,
                'permissions' => [
                    'members.view',

                    'drive.view',
                    'drive.upload',
                    'drive.download',
                    'drive.share',

                    'posts.view',
                    'posts.create',
                    'posts.update',

                    'chat.view',
                ],
            ],

            [
                'name' => 'Member',
                'description' => 'Standard portal access for church members.',
                'is_system' => true,
                'permissions' => [
                    'drive.view',
                    'drive.download',

                    'posts.view',

                    'chat.view',
                ],
            ],
        ];

        foreach ($roles as $data) {

            $role = Role::updateOrCreate(
                [
                    'name' => $data['name'],
                    'guard_name' => 'web',
                ],
                [
                    'description' => $data['description'],
                    'is_system' => $data['is_system'],
                ]
            );

            $role->syncPermissions($data['permissions']);
        }
    }
}
