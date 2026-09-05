<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FilePermission;
use App\Models\Ministry;
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

        $file->permissions->loadMorph('principal', [
            Ministry::class => [
                'localChurch',
            ],
        ]);

        $permissions = $file->permissions->map(function ($permission) {
            return [
                'id' => $permission->id,
                'folder_id' => $permission->folder_id,
                'principal_type' => $permission->principal_type,
                'principal_name' => $permission->principal->name,
                'principal_local_church' => $permission->principal_type === 'ministry'
                    ? $permission->principal->localChurch?->name
                    : null,
                'role' => $permission->role,
                'updated_at' => $permission->updated_at,
                'created_at' => $permission->created_at,
            ];
        });

        return response()->json([
            'owner' => $file->uploader,
            'permissions' => $permissions,
            'visibility' => $file->visibility,
            'share_token' => $file->share_token,
        ]);
    }

    public function store(Request $request, File $file)
    {
        $request->validate([
            'principal_type' => 'required|in:user,church,cluster,ministry,role',
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

        if (
            $file->folder &&
            $file->folder->visibility === 'private' &&
            $request->visibility === 'public'
        ) {
            return response()->json([
                'errors' => [
                    'visibility' => [
                        'A file inside a restricted folder cannot be public.',
                    ],
                ],
            ], 422);
        }

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
