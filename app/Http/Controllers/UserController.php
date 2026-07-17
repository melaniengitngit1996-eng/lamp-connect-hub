<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        abort_unless(
            auth()->user()->can('users.view'),
            403
        );

        return User::query()
            ->with('roles:id,name')
            ->with('localChurch')
            ->with('clusters')
            ->with('ministries')
            ->latest()
            ->get();
    }

    public function pending()
    {
        abort_unless(
            auth()->user()->can('users.view'),
            403
        );

        return User::query()
            ->where('status', User::STATUS_PENDING)
            ->with('localChurch')
            ->select([
                'id',
                'name',
                'email',
                'member_id',
                'local_church_id',
                'created_at',
            ])
            ->latest()
            ->get();
    }

    public function approve(User $user)
    {
        abort_unless(
            auth()->user()->can('members.approve'),
            403
        );

        if ($user->status !== User::STATUS_PENDING) {
            return response()->json([
                'message' => 'User is no longer pending approval.',
            ], 422);
        }

        $user->update([
            'status' => User::STATUS_APPROVED,
        ]);

        return response()->json([
            'message' => 'User approved successfully.',
        ]);
    }

    public function reject(User $user)
    {
        abort_unless(
            auth()->user()->can('members.approve'),
            403
        );

        $user->update([
            'status' => User::STATUS_REJECTED,
        ]);

        return response()->json([
            'message' => 'User rejected successfully.',
        ]);
    }

    public function destroy(User $user)
    {
        abort_unless(
            auth()->user()->can('users.delete'),
            403
        );

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'local_church_id' => ['nullable', 'exists:local_churches,id'],

            'ministry_ids' => ['array'],
            'ministry_ids.*' => ['exists:ministries,id'],

            'cluster_ids' => ['array'],
            'cluster_ids.*' => ['exists:clusters,id'],

            'role_ids' => ['array'],
            'role_ids.*' => ['exists:roles,id'],

            'status' => ['required', Rule::in([
                'pending',
                'approved',
                'rejected',
            ])],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'local_church_id' => $validated['local_church_id'],
            'status' => $validated['status'],
        ]);

        $user->ministries()->sync($validated['ministry_ids'] ?? []);
        $user->clusters()->sync($validated['cluster_ids'] ?? []);

        $roleNames = Role::whereIn('id', $validated['role_ids'] ?? [])
            ->pluck('name');

        $user->syncRoles($roleNames);

        return response()->json($user, 201);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user),
            ],
            'local_church_id' => ['nullable', 'exists:local_churches,id'],

            'ministry_ids' => ['array'],
            'ministry_ids.*' => ['exists:ministries,id'],

            'cluster_ids' => ['array'],
            'cluster_ids.*' => ['exists:clusters,id'],

            'role_ids' => ['array'],
            'role_ids.*' => ['exists:roles,id'],

            'status' => ['required', Rule::in([
                'pending',
                'approved',
                'rejected',
            ])],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'local_church_id' => $validated['local_church_id'],
            'status' => $validated['status'],
        ]);

        $user->ministries()->sync($validated['ministry_ids'] ?? []);
        $user->clusters()->sync($validated['cluster_ids'] ?? []);

        $roleNames = Role::whereIn('id', $validated['role_ids'] ?? [])
            ->pluck('name');

        $user->syncRoles($roleNames);

        return response()->json($user);
    }
}
