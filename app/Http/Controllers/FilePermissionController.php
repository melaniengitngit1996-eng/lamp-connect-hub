<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FilePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FilePermissionController extends Controller
{
    public function index(File $file)
    {
        $file->load([
            'uploader',
            'permissions',
        ]);

        return response()->json([
            'owner' => $file->uploader,
            'permissions' => $file->permissions,
            'visibility' => $file->visibility,
            'share_token' => $file->share_token,
        ]);
    }

    public function store(Request $request, File $file)
    {
        $request->validate([
            'principal_type' => 'required|in:user,church,cluster,ministry',
            'principal_id' => 'required',
            'role' => 'required|in:viewer,contributor,manager',
        ]);

        $permission = $file->permissions()->updateOrCreate(
            [
                'principal_type' => $request->principal_type,
                'principal_id' => $request->principal_id,
            ],
            [
                'role' => $request->role,
            ]
        );

        return response()->json($permission);
    }

    public function update(Request $request, FilePermission $permission)
    {
        $request->validate([
            'role' => 'required|in:viewer,contributor,manager',
        ]);

        $permission->update([
            'role' => $request->role,
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function destroy(FilePermission $permission)
    {
        $permission->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    public function updateVisibility(Request $request, File $file)
    {
        $request->validate([
            'visibility' => 'required|in:private,public,link',
        ]);

        if ($request->visibility === 'link') {
            if (!$file->share_token) {
                $file->share_token = Str::uuid();
            }
        } else {
            $file->share_token = null;
        }

        $file->visibility = $request->visibility;

        $file->save();

        return response()->json([
            'success' => true,
            'share_token' => $file->share_token,
        ]);
    }
}
