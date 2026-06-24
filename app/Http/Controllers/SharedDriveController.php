<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FileFolder;
use App\Models\File;

class SharedDriveController extends Controller
{
    public function folder(string $token)
    {
        $folder = FileFolder::where(
            'share_token',
            $token
        )->firstOrFail();

        $folder->load([
            'owner',
            'files.uploader',
        ]);

        abort_unless(
            $folder->visibility === 'link',
            404
        );

        return response()->json([
            'folder' => $folder,
            'files' => $folder->files
        ]);
    }

    public function file(string $token)
    {
        $file = File::where(
            'share_token',
            $token
        )->firstOrFail();

        $file->load([
            'uploader',
        ]);

        abort_unless(
            $file->visibility === 'link',
            404
        );

        return response()->json([
            'file' => $file,
        ]);
    }
}
