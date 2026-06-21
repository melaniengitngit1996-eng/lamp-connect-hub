<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/drive/folders', function (Request $request) {
    $parentId = $request->parent_id;

    return response()->json([
        'folders' => \App\Models\FileFolder::with('owner')
            ->where('parent_id', $parentId)
            ->get(),

        'files' => [],
    ]);
});
