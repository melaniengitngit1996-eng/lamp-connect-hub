<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\FileActivity;
use Illuminate\Validation\Rule;
use App\Models\DriveFavorite;

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
            ],
            [
                'file.max' => "The file must not be greater than {$maxUploadSize} MB.",
            ]
        );

        $uploadedFile = $request->file('file');

        $filename = $uploadedFile->getClientOriginalName();

        $exists = File::where('folder_id', $request->folder_id)
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
            'visibility' => 'private'
        ]);

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

        $file->update([
            'folder_id' => $validated['folder_id'],
        ]);

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
