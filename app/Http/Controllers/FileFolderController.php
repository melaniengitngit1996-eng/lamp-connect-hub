<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\FileFolder;
use App\Models\FolderPermission;
use App\Models\File;

class FileFolderController extends Controller
{
    // show all/per file folder
    public function index(Request $request)
    {
        $search = trim($request->search ?? '');

        if ($search) {
            return response()->json([
                'folders' => FileFolder::with('owner')
                    ->visibleTo(Auth::user())
                    ->where('name', 'like', "%{$search}%")
                    ->latest()
                    ->get()
                    ->map(function ($folder) {
                        $folder->can_manage = $folder->canManage(Auth::user());

                        return $folder;
                    }),

                'files' => File::with('uploader')
                    ->visibleTo(Auth::user())
                    ->where(function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('original_name', 'like', "%{$search}%");
                    })
                    ->latest()
                    ->get()
                    ->map(function ($file) {
                        $file->can_manage = $file->canManage(Auth::user());

                        return $file;
                    }),
            ]);
        }

        return response()->json([
            'folders' => FileFolder::with('owner')
                ->visibleTo(Auth::user())
                ->where('parent_id', $request->parent_id)
                ->latest()
                ->get()
                ->map(function ($folder) {
                    $folder->can_manage = $folder->canManage(Auth::user());

                    return $folder;
                }),

            'files' => File::with('uploader')
                ->visibleTo(Auth::user())
                ->where('folder_id', $request->parent_id)
                ->latest()
                ->get()
                ->map(function ($file) {
                    $file->can_manage = $file->canManage(Auth::user());

                    return $file;
                }),
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
            'visibility' => 'private'
        ]);

        return response()->json([
            'message' => 'Folder created successfully.',
            'folder' => $folder,
        ], 201);
    }

    // delete folder
    public function destroy(FileFolder $folder)
    {
        abort_unless(
            $folder->owner_id === Auth::id(),
            403
        );

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

        foreach ($folder->files as $file) {
            Storage::disk($file->disk)->delete($file->path);

            $file->delete();
        }

        $folder->delete();
    }

    public function share(
        Request $request,
        FileFolder $folder
    ) {
        abort_unless(
            $folder->owner_id === Auth::id(),
            403
        );

        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'role' => ['required', 'in:viewer,editor'],
        ]);

        FolderPermission::updateOrCreate(
            [
                'folder_id' => $folder->id,
                'user_id' => $request->user_id,
            ],
            [
                'role' => $request->role,
            ]
        );

        $folder->update([
            'visibility' => 'shared',
        ]);

        return response()->json([
            'message' => 'Folder shared.',
        ]);
    }
}
