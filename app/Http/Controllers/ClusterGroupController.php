<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use App\Models\LocalChurch;
use Illuminate\Http\Request;

class ClusterGroupController extends Controller
{
    public function index(LocalChurch $localChurch)
    {
        return $localChurch->clusters()
            ->orderBy('name')
            ->get();
    }
}
