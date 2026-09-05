<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\FileActivity;
use Illuminate\Validation\Rule;
use App\Models\DriveFavorite;
use App\Models\FileFolder;
use App\Models\FilePermission;

class FileController extends Controller
{
    public function store(Request $request)
    {
        $maxUploadSize = (int) setting('drive.max_upload_size', 50);

        $validated = $request->validate(
            [
                'file' => [
                    'required',
                    'file',
                    'max:' . ($maxUploadSize * 1024),
                ],
                'folder_id' => [
                    'nullable',
                    'exists:file_folders,id',
                ],
                'visibility' => [
                    'required',
                    'in:inherit,private,public,link',
                ],
            ],
            [
                'file.max' => "The file must not be greater than {$maxUploadSize} MB.",
            ]
        );

        $uploadedFile = $request->file('file');

        $filename = $uploadedFile->getClientOriginalName();

        $exists = File::where('folder_id', $validated['folder_id'] ?? null)
            ->where('name', $filename)
            ->exists();

        if ($exists) {
            return response()->json([
                'errors' => [
                    'file' => [
                        'A file with this name already exists in this folder.',
                    ],
                ],
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Resolve parent folder and visibility
        |--------------------------------------------------------------------------
        */

        $visibility = $validated['visibility'];

        $folder = null;

        if ($validated['folder_id']) {
            $folder = FileFolder::find($validated['folder_id']);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate file visibility against parent folder
        |--------------------------------------------------------------------------
        */

        if ($folder && $visibility !== 'inherit') {
            $visibilityLevels = [
                'private' => 1,
                'link' => 2,
                'public' => 3,
            ];

            if (
                $visibilityLevels[$visibility]
                > $visibilityLevels[$folder->visibility]
            ) {
                return response()->json([
                    'errors' => [
                        'visibility' => [
                            'The file cannot have broader access than its parent folder.',
                        ],
                    ],
                ], 422);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Inherit parent folder visibility
        |--------------------------------------------------------------------------
        */

        $inheritPermissions = $visibility === 'inherit';

        if ($inheritPermissions) {
            $visibility = $folder?->visibility ?? 'private';
        }

        /*
        |--------------------------------------------------------------------------
        | Store file
        |--------------------------------------------------------------------------
        */

        $path = $uploadedFile->store('drive', 'public');

        $file = File::create([
            'folder_id' => $validated['folder_id'] ?? null,
            'name' => $uploadedFile->getClientOriginalName(),
            'original_name' => $uploadedFile->getClientOriginalName(),
            'mime_type' => $uploadedFile->getMimeType(),
            'extension' => $uploadedFile->getClientOriginalExtension(),
            'size' => $uploadedFile->getSize(),
            'disk' => 'public',
            'path' => $path,
            'uploaded_by' => Auth::id(),
            'visibility' => $visibility,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Inherit parent folder permissions
        |--------------------------------------------------------------------------
        */

        if ($inheritPermissions && $folder) {
            $folder->load('permissions');

            foreach ($folder->permissions as $permission) {
                FilePermission::create([
                    'file_id' => $file->id,
                    'principal_type' => $permission->principal_type,
                    'principal_id' => $permission->principal_id,
                    'role' => $permission->role,
                ]);
            }
        }

        return response()->json([
            'file' => $file,
        ], 201);
    }

    public function destroy(File $file)
    {
        Storage::disk($file->disk)->delete($file->path);

        $file->delete();

        return response()->json([
            'message' => 'File deleted successfully.',
        ]);
    }

    public function logView(File $file)
    {
        $file->logActivity('viewed');

        return $file;
    }

    public function logDownload(File $file)
    {
        $file->logActivity('downloaded');

        return $file;
    }

    public function activities(File $file)
    {
        $views = $file->activities()
            ->with('user:id,name,created_at')
            ->where('action', 'viewed')
            ->latest()
            ->get();

        $downloads = $file->activities()
            ->with('user:id,name,created_at')
            ->where('action', 'downloaded')
            ->latest()
            ->get();

        return response()->json([
            'views' => $views,
            'downloads' => $downloads,
            'views_count' => $views->count(),
            'downloads_count' => $downloads->count(),
        ]);
    }

    public function update(Request $request, File $file)
    {
        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('files', 'name')
                        ->ignore($file->id)
                        ->where(fn($query) => $query
                            ->where('folder_id', $file->folder_id)
                            ->whereNull('deleted_at')),
                ],
            ],
            [
                'name.unique' => 'A file with this name already exists in this folder.',
            ]
        );

        $file->update([
            'name' => $validated['name'],
        ]);

        return response()->json([
            'message' => 'File renamed successfully.',
            'file' => $file,
        ]);
    }

    public function move(Request $request, File $file)
    {
        $validated = $request->validate([
            'folder_id' => [
                'nullable',
                'exists:file_folders,id',
            ],
        ]);

        $destinationFolder = null;

        if ($validated['folder_id']) {
            $destinationFolder = FileFolder::find(
                $validated['folder_id']
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Adjust file visibility when moving into a restricted folder
    |--------------------------------------------------------------------------
    */

        if (
            $destinationFolder &&
            $destinationFolder->visibility === 'private' &&
            $file->visibility === 'public'
        ) {
            $file->visibility = 'private';
            $file->share_token = null;
        }

        $file->folder_id = $validated['folder_id'];
        $file->save();

        return response()->json([
            'message' => 'File moved successfully.',
        ]);
    }

    public function toggleFavorite(File $file)
    {
        $favorite = $file->favorites()
            ->where('user_id', Auth::id())
            ->first();

        if ($favorite) {
            $favorite->delete();

            return response()->json([
                'favorited' => false,
            ]);
        }

        $file->favorites()->create([
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'favorited' => true,
        ]);
    }
}
