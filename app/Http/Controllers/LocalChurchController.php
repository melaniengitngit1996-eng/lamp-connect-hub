<?php

namespace App\Http\Controllers;

use App\Models\LocalChurch;
use Illuminate\Http\Request;

class LocalChurchController extends Controller
{
    public function index()
    {
        return LocalChurch::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);
    }
}
