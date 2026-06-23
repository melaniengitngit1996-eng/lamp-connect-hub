<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Cluster;
use App\Models\Ministry;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::take(5)->get();

        $cluster = Cluster::first();
        $ministry = Ministry::first();

        foreach ($users as $user) {

            $user->update([
                'local_church_id' => 1,
            ]);

            $user->clusters()->syncWithoutDetaching([
                $cluster->id,
            ]);

            $user->ministries()->syncWithoutDetaching([
                $ministry->id,
            ]);
        }
    }
}
