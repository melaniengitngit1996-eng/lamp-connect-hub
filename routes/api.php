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

Route::post('/drive/folders', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'parent_id' => ['nullable', 'integer', 'exists:file_folders,id'],
    ]);

    $folder = \App\Models\FileFolder::create([
        'name' => $validated['name'],
        'parent_id' => $validated['parent_id'] ?? null,
        'owner_id' => Auth::id(),
    ]);

    return response()->json([
        'message' => 'Folder created successfully.',
        'folder' => $folder,
    ], 201);
});
