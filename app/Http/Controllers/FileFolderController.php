<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\FileFolder;
use App\Models\FolderPermission;
use App\Models\File;
use Illuminate\Validation\Rule;

class FileFolderController extends Controller
{
    // show all/per file folder
    public function index(Request $request)
    {
        abort_unless(
            auth()->user()->can('drive.view'),
            403
        );

        $search = trim($request->search ?? '');

        if ($request->boolean('folders_only')) {
            $parentId = $request->input('parent_id');

            return response()->json([
                'folders' => FileFolder::with([
                    'owner',
                    'favorites' => fn($query) =>
                    $query->where('user_id', Auth::id()),
                ])
                    ->visibleTo(Auth::user())
                    ->where('parent_id', $parentId)
                    ->where('name', 'like', "%{$search}%")
                    ->latest()
                    ->get()
                    ->map(function ($folder) {
                        $folder->can_manage = $folder->canManage(Auth::user());

                        return $folder;
                    }),
            ]);
        }

        if ($request->boolean('starred')) {
            $parentId = $request->input('parent_id');

            return response()->json([
                'folders' => FileFolder::with([
                    'owner',
                    'favorites' => fn($query) =>
                    $query->where('user_id', Auth::id()),
                ])
                    ->visibleTo(Auth::user())
                    ->whereHas('favorites', function ($query) {
                        $query->where('user_id', Auth::id());
                    })
                    ->when(
                        $request->has('parent_id'),
                        fn($query) => $query->where('parent_id', $parentId)
                    )
                    ->latest()
                    ->get()
                    ->map(function ($folder) {
                        $folder->can_manage = $folder->canManage(Auth::user());
                        $folder->is_favorited = true;

                        return $folder;
                    }),

                'files' => File::with([
                    'uploader',
                    'favorites' => fn($query) =>
                    $query->where('user_id', Auth::id()),
                ])
                    ->visibleTo(Auth::user())
                    ->whereHas('favorites', function ($query) {
                        $query->where('user_id', Auth::id());
                    })
                    ->when(
                        $request->has('parent_id'),
                        fn($query) => $query->where('folder_id', $parentId)
                    )
                    ->latest()
                    ->get()
                    ->map(function ($file) {
                        $file->can_manage = $file->canManage(Auth::user());
                        $file->is_favorited = true;

                        return $file;
                    }),
            ]);
        }
        if ($search) {
            return response()->json([
                'folders' => FileFolder::with('owner')
                    ->visibleTo(Auth::user())
                    ->where('name', 'like', "%{$search}%")
                    ->latest()
                    ->get()
                    ->map(function ($folder) {
                        $folder->can_manage = $folder->canManage(Auth::user());
                        $folder->is_favorited = $folder->favorites->isNotEmpty();

                        return $folder;
                    }),

                'files' => File::with([
                    'uploader',
                    'favorites' => fn($query) =>
                    $query->where('user_id', Auth::id()),
                ])
                    ->visibleTo(Auth::user())
                    ->where(function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('original_name', 'like', "%{$search}%");
                    })
                    ->latest()
                    ->get()
                    ->map(function ($file) {
                        $file->can_manage = $file->canManage(Auth::user());
                        $file->is_favorited = $file->favorites->isNotEmpty();

                        return $file;
                    }),
            ]);
        }

        return response()->json([
            'folders' => FileFolder::with([
                'owner',
                'favorites' => fn($query) =>
                $query->where('user_id', Auth::id()),
            ])
                ->visibleTo(Auth::user())
                ->where('parent_id', $request->parent_id)
                ->latest()
                ->get()
                ->map(function ($folder) {
                    $folder->can_manage = $folder->canManage(Auth::user());
                    $folder->is_favorited = $folder->favorites->isNotEmpty();

                    return $folder;
                }),

            'files' => File::with([
                'uploader',
                'favorites' => fn($query) =>
                $query->where('user_id', Auth::id()),
            ])
                ->visibleTo(Auth::user())
                ->where('folder_id', $request->parent_id)
                ->latest()
                ->get()
                ->map(function ($file) {
                    $file->can_manage = $file->canManage(Auth::user());
                    $file->is_favorited = $file->favorites->isNotEmpty();

                    return $file;
                }),
        ]);
    }

    // create new folder
    public function store(Request $request)
    {
        abort_unless(
            auth()->user()->can('drive.upload'),
            403
        );

        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('file_folders', 'name')
                        ->where(fn($query) => $query
                            ->where('parent_id', $request->parent_id)
                            ->whereNull('deleted_at')),
                ],
            ],
            [
                'name.required' => 'Please enter a folder name.',
                'name.unique' => 'A folder with this name already exists in this location.',
            ]
        );

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
            auth()->user()->can('drive.delete'),
            403
        );

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
        abort_unless(
            auth()->user()->can('drive.delete'),
            403
        );

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
            auth()->user()->can('drive.share'),
            403
        );

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

    public function update(Request $request, FileFolder $folder)
    {
        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('file_folders', 'name')
                        ->ignore($folder->id)
                        ->where(fn($query) => $query
                            ->where('parent_id', $folder->parent_id)
                            ->whereNull('deleted_at')),
                ],
            ],
            [
                'name.unique' => 'A folder with this name already exists in this location.',
            ]
        );

        $folder->update([
            'name' => $validated['name'],
        ]);

        return response()->json([
            'message' => 'Folder renamed successfully.',
            'folder' => $folder,
        ]);
    }

    public function move(Request $request, FileFolder $folder)
    {
        $validated = $request->validate([
            'parent_id' => [
                'nullable',
                'exists:file_folders,id',
            ],
        ]);

        if (($validated['parent_id'] ?? null) == $folder->id) {
            return response()->json([
                'folder' => [
                    'A folder cannot be moved into itself.',
                ],
            ], 422);
        }

        $destination = isset($validated['parent_id'])
            ? FileFolder::find($validated['parent_id'])
            : null;

        if ($destination && $folder->isAncestorOf($destination)) {
            return response()->json([
                'folder' => [
                    'A folder cannot be moved into one of its subfolders.',
                ],
            ]);
        }

        $folder->update([
            'parent_id' => $validated['parent_id'],
        ]);

        return response()->json([
            'message' => 'Folder moved successfully.',
        ]);
    }

    public function toggleFavorite(FileFolder $folder)
    {
        $favorite = $folder->favorites()
            ->where('user_id', Auth::id())
            ->first();

        if ($favorite) {
            $favorite->delete();

            return response()->json([
                'favorited' => false,
            ]);
        }

        $folder->favorites()->create([
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'favorited' => true,
        ]);
    }
}
