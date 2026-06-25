<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invitation;
use App\Models\LocalChurch;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Mail\InvitationMail;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required'],
            'full_name' => ['required'],
            'email' => ['required', 'email'],
            'local_church' => ['nullable'],
        ]);

        $existing = Invitation::where('member_id', $validated['member_id'])
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'token' => $existing->token,
                'signup_url' => config('app.url') . '/signup/' . $existing->token,
            ]);
        }

        $invitation = Invitation::create([
            'member_id' => $validated['member_id'],
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'local_church' => $validated['local_church'],
            'token' => Str::uuid(),
            'expires_at' => now()->addDays(7),
        ]);

        $signupUrl = config('app.frontend_url')
            . '/signup/'
            . $invitation->token;

        Mail::to($invitation->email)
            ->send(
                new InvitationMail(
                    $invitation,
                    $signupUrl
                )
            );

        return response()->json([
            'success' => true,
            'token' => $invitation->token,
            'signup_url' => $signupUrl,
        ]);
    }

    public function show(string $token)
    {
        $invitation = Invitation::where('token', $token)->first();

        if (! $invitation) {
            return response()->json([
                'message' => 'Invitation not found.',
            ], 404);
        }

        if ($invitation->accepted_at) {
            return response()->json([
                'message' => 'Invitation has already been used.',
            ], 422);
        }

        if (
            $invitation->expires_at &&
            $invitation->expires_at->isPast()
        ) {
            return response()->json([
                'message' => 'Invitation has expired.',
            ], 422);
        }

        return response()->json([
            'full_name' => $invitation->full_name,
            'email' => $invitation->email,
            'local_church' => $invitation->local_church,
        ]);
    }

    public function signup(Request $request, string $token)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $invitation = Invitation::where('token', $token)->first();

        if (! $invitation) {
            return response()->json([
                'message' => 'Invitation not found.',
            ], 404);
        }

        if ($invitation->accepted_at) {
            return response()->json([
                'message' => 'Invitation has already been used.',
            ], 422);
        }

        if (
            $invitation->expires_at &&
            $invitation->expires_at->isPast()
        ) {
            return response()->json([
                'message' => 'Invitation has expired.',
            ], 422);
        }

        $existingUser = User::where(
            'email',
            $invitation->email
        )->exists();

        if ($existingUser) {
            return response()->json([
                'message' => 'An account already exists for this email.',
            ], 422);
        }

        $localChurch = LocalChurch::whereRaw(
            'LOWER(name) = ?',
            [strtolower(trim($invitation->local_church))]
        )->first();

        if (! $localChurch) {
            return response()->json([
                'message' => 'Local church not found.',
            ], 422);
        }

        DB::transaction(function () use (
            $validated,
            $invitation,
            $localChurch
        ) {
            User::create([
                'member_id' => $invitation->member_id,
                'local_church_id' => $localChurch->id,
                'name' => $invitation->full_name,
                'email' => $invitation->email,
                'password' => Hash::make($validated['password']),
            ]);

            $invitation->update([
                'accepted_at' => now(),
            ]);
        });

        return response()->json([
            'message' => 'Account created successfully and is awaiting approval.',
        ]);
    }
}
