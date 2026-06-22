<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\FileFolder;

class FileFolderController extends Controller
{
    // show all/per file folder
    public function index(Request $request)
    {
        $parentId = $request->parent_id;

        return response()->json([
            'folders' => FileFolder::with('owner')
                ->where('parent_id', $parentId)
                ->get(),

            'files' => [],
        ]);
    }

    // create new folder
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer', 'exists:file_folders,id'],
        ]);

        $folder = FileFolder::create([
            'name' => $validated['name'],
            'parent_id' => $validated['parent_id'] ?? null,
            'owner_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Folder created successfully.',
            'folder' => $folder,
        ], 201);
    }

    // delete folder
    public function destroy(FileFolder $folder)
    {
        $this->deleteFolderRecursively($folder);

        return response()->json([
            'message' => 'Folder deleted successfully.',
        ]);
    }

    // delete sub folder
    private function deleteFolderRecursively(FileFolder $folder): void
    {
        foreach ($folder->children as $child) {
            $this->deleteFolderRecursively($child);
        }

        $folder->delete();
    }
}
