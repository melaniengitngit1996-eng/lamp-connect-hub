<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        return Role::query()
            ->with([
                'permissions:id,name,description',
            ])
            ->withCount([
                'users',
                'permissions',
            ])
            ->orderBy('name')
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'unique:roles,name'],
            'description' => ['nullable'],
            'permissions' => ['array'],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($validated['permissions']);

        return response()->json($role);
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('roles')->ignore($role->id),
            ],
            'description' => ['nullable'],
            'permissions' => ['array'],
        ]);

        $role->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        $role->syncPermissions($validated['permissions']);

        return response()->json($role);
    }

    public function destroy(Role $role)
    {
        if ($role->is_system) {
            return response()->json([
                'message' => 'System roles cannot be deleted.'
            ], 422);
        }

        if ($role->users()->exists()) {
            return response()->json([
                'message' => 'This role is currently assigned to one or more users.'
            ], 422);
        }

        $role->delete();

        return response()->noContent();
    }
}
