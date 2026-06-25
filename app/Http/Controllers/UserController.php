<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return User::query()
            ->with('localChurch')
            ->latest()
            ->get();
    }

    public function pending()
    {
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
        $user->update([
            'status' => User::STATUS_REJECTED,
        ]);

        return response()->json([
            'message' => 'User rejected successfully.',
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }
}
