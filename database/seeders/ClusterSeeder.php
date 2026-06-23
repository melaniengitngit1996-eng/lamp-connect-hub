<?php

namespace Database\Seeders;

use App\Models\Cluster;
use App\Models\LocalChurch;
use Illuminate\Database\Seeder;

class ClusterSeeder extends Seeder
{
    public function run(): void
    {
        $church = LocalChurch::first();

        Cluster::insert([
            [
                'local_church_id' => $church->id,
                'name' => 'Cluster 1',
            ],
            [
                'local_church_id' => $church->id,
                'name' => 'Cluster 2',
            ],
            [
                'local_church_id' => $church->id,
                'name' => 'Cluster 3',
            ],
        ]);
    }
}
