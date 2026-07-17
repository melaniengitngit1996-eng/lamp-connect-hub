<?php

namespace App\Http\Controllers;

use App\Models\LocalChurch;
use App\Models\Ministry;
use Illuminate\Http\Request;

class MinistryController extends Controller
{
    public function index(LocalChurch $localChurch)
    {
        return $localChurch->ministries()
            ->orderBy('name')
            ->get();
    }
}
