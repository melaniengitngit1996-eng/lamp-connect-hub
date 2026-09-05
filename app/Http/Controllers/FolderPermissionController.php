<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Cluster;
use App\Models\FileFolder;
use App\Models\LocalChurch;
use App\Models\Ministry;
use App\Models\User;
use App\Models\FolderPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class FolderPermissionController extends Controller
{
    public function index(FileFolder $folder)
    {
        $folder->load([
            'owner',
            'permissions',
        ]);

        $folder->permissions->loadMorph('principal', [
            Ministry::class => [
                'localChurch',
            ],
        ]);

        $permissions = $folder->permissions->map(function ($permission) {
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
            'owner' => $folder->owner,
            'permissions' => $permissions,
            'visibility' => $folder->visibility,
            'share_token' => $folder->share_token,
        ]);
    }

    public function search(Request $request)
    {
        $search = trim($request->q);

        $results = collect();

        // Users
        $users = User::query()
            ->where('name', 'like', "%{$search}%")
            ->limit(10)
            ->get()
            ->map(fn($user) => [
                'type' => 'user',
                'id' => $user->id,
                'label' => $user->name,
                'subtitle' => $user->email,
            ]);

        // Churches
        $churches = LocalChurch::query()
            ->where('name', 'like', "%{$search}%")
            ->limit(10)
            ->get()
            ->map(fn($church) => [
                'type' => 'church',
                'id' => $church->id,
                'label' => $church->name,
                'subtitle' => 'Local Church',
            ]);

        // Clusters
        $clusters = Cluster::query()
            ->where('name', 'like', "%{$search}%")
            ->limit(10)
            ->get()
            ->map(fn($cluster) => [
                'type' => 'cluster',
                'id' => $cluster->id,
                'label' => $cluster->name,
                'subtitle' => 'Cluster',
            ]);

        // Ministries
        $ministries = Ministry::query()
            ->with('localChurch')
            ->where('name', 'like', "%{$search}%")
            ->limit(10)
            ->get()
            ->map(fn($ministry) => [
                'type' => 'ministry',
                'id' => $ministry->id,
                'label' => $ministry->localChurch
                    ? "{$ministry->name} - {$ministry->localChurch->name}"
                    : $ministry->name,
                'subtitle' => 'Ministry',
            ]);

        // Roles
        $roles = Role::query()
            ->where('name', 'like', "%{$search}%")
            ->limit(10)
            ->get()
            ->map(fn($role) => [
                'type' => 'role',
                'id' => $role->id,
                'label' => $role->name,
                'subtitle' => 'Role',
            ]);

        return response()->json(
            $results
                ->merge($users)
                ->merge($churches)
                ->merge($clusters)
                ->merge($ministries)
                ->merge($roles)
                ->values()
        );
    }

    public function store(Request $request, FileFolder $folder)
    {
        $validated = $request->validate([
            'principal_type' => 'required|in:user,church,cluster,ministry,role',
            'principal_id' => 'required',
            'role' => 'required|in:viewer,contributor,manager',
            'apply_to_files' => 'boolean',
        ]);

        $permission = $folder->permissions()->updateOrCreate(
            [
                'principal_type' => $validated['principal_type'],
                'principal_id' => $validated['principal_id'],
            ],
            [
                'role' => $validated['role'],
            ]
        );

        if ($request->boolean('apply_to_files')) {
            $this->applyPermissionToFiles(
                $folder,
                $validated['principal_type'],
                $validated['principal_id'],
                $validated['role']
            );
        }

        return response()->json($permission);
    }

    private function applyPermissionToFiles(FileFolder $folder, string $principalType, int|string $principalId, string $role): void
    {
        $folder->load([
            'files',
            'children',
        ]);

        foreach ($folder->files as $file) {
            $file->permissions()->updateOrCreate(
                [
                    'principal_type' => $principalType,
                    'principal_id' => $principalId,
                ],
                [
                    'role' => $role,
                ]
            );
        }

        foreach ($folder->children as $childFolder) {
            $this->applyPermissionToFiles(
                $childFolder,
                $principalType,
                $principalId,
                $role
            );
        }
    }

    public function update(Request $request, FolderPermission $permission)
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

    public function destroy(FolderPermission $permission)
    {
        $permission->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    public function updateVisibility(Request $request, FileFolder $folder)
    {
        $request->validate([
            'visibility' => 'required|in:private,public,link',
        ]);

        $oldVisibility = $folder->visibility;
        $newVisibility = $request->visibility;

        // Public → Restricted
        if (
            $oldVisibility === 'public' &&
            $newVisibility === 'private'
        ) {
            $folder->restrictPublicFiles();
        }

        if ($newVisibility === 'link') {
            if (!$folder->share_token) {
                $folder->share_token = Str::uuid();
            }
        } else {
            $folder->share_token = null;
        }

        $folder->visibility = $newVisibility;

        $folder->save();

        return response()->json([
            'success' => true,
            'share_token' => $folder->share_token,
        ]);
    }
}
