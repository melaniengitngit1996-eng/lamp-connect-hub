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

class FolderPermissionController extends Controller
{
    public function index(FileFolder $folder)
    {
        $folder->load([
            'owner',
            'permissions',
        ]);

        return response()->json([
            'owner' => $folder->owner,
            'permissions' => $folder->permissions,
            'visibility' => $folder->visibility,
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
            ->where('name', 'like', "%{$search}%")
            ->limit(10)
            ->get()
            ->map(fn($ministry) => [
                'type' => 'ministry',
                'id' => $ministry->id,
                'label' => $ministry->name,
                'subtitle' => 'Ministry',
            ]);

        return response()->json(
            $results
                ->merge($users)
                ->merge($churches)
                ->merge($clusters)
                ->merge($ministries)
                ->values()
        );
    }

    public function store(Request $request, FileFolder $folder)
    {
        $request->validate([
            'principal_type' => 'required',
            'principal_id' => 'required',
            'role' => 'required|in:viewer,contributor,manager',
        ]);

        $permission = $folder->permissions()->updateOrCreate(
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
            'visibility' => 'required|in:private,public',
        ]);

        $folder->update([
            'visibility' => $request->visibility,
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
