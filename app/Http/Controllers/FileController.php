<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\File;

class FileController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'max:10240'], // 10MB
            'folder_id' => ['nullable', 'exists:file_folders,id'],
        ]);

        $uploadedFile = $request->file('file');

        $path = $uploadedFile->store('drive', 'public');

        $file = File::create([
            'folder_id' => $validated['folder_id'] ?? null,
            'name' => pathinfo($uploadedFile->hashName(), PATHINFO_FILENAME),
            'original_name' => $uploadedFile->getClientOriginalName(),
            'mime_type' => $uploadedFile->getMimeType(),
            'extension' => $uploadedFile->getClientOriginalExtension(),
            'size' => $uploadedFile->getSize(),
            'disk' => 'public',
            'path' => $path,
            'uploaded_by' => Auth::id(),
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
}
