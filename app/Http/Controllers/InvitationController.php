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
use App\Models\Cluster;
use App\Models\Ministry;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class InvitationController extends Controller
{
    public function index()
    {
        return Invitation::latest()
            ->get()
            ->map(function ($invitation) {
                return [
                    'id' => $invitation->id,
                    'full_name' => $invitation->full_name,
                    'email' => $invitation->email,
                    'initials' => Str::of($invitation->full_name)
                        ->explode(' ')
                        ->map(fn($name) => Str::substr($name, 0, 1))
                        ->take(2)
                        ->implode(''),
                    'local_church' => $invitation->local_church,
                    'invited_ago' => $invitation->created_at->diffForHumans(),
                    'status' => $invitation->status,
                ];
            });
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required'],
            'email' => ['required', 'email'],
            'local_church' => ['nullable'],
            'member_id' => ['nullable'],

            'ministries' => ['array'],
            'ministries.*.name' => ['required', 'string'],
            'ministries.*.local_church' => ['nullable', 'string'],

            'cluster_groups' => ['array'],
            'cluster_groups.*.name' => ['required', 'string'],
            'cluster_groups.*.local_church' => ['nullable', 'string'],
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
            'ministries' => $validated['ministries'] ?? [],
            'cluster_groups' => $validated['cluster_groups'] ?? [],
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
            'username' => $this->generateUsername($invitation->full_name),
        ]);
    }

    public function signup(Request $request, string $token)
    {
        $validated = $request->validate([
            'username' => [
                'required',
                'min:3',
                'max:30',
                'alpha_dash',
                Rule::unique('users', 'username'),
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8',
            ],
        ]);

        $invitation = Invitation::where('token', $token)->first();

        if (
            User::where('email', $invitation->email)->exists()
        ) {
            return response()->json([
                'errors' => [
                    'email' => [
                        'The email has already been taken.',
                    ],
                ]
            ], 422);
        }

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

        $localChurchId = $this->resolveLocalChurchId(
            $invitation->local_church
        );

        DB::transaction(function () use (
            $validated,
            $invitation,
            $localChurchId
        ) {
            $autoApprove = setting(
                'general.auto_approve_members',
                false
            );

            $member = User::create([
                'member_id' => $invitation->member_id,
                'local_church_id' => $localChurchId,
                'name' => $invitation->full_name,
                'username' => $validated['username'],
                'email' => $invitation->email,
                'password' => Hash::make($validated['password']),
                'status' => $autoApprove ? 'approved' : 'pending',
            ]);

            $member->syncRoles(['Member']);

            // Attach ministries
            foreach ($invitation->ministries ?? [] as $item) {
                if (empty($item['name'])) {
                    continue;
                }

                $churchId = $this->resolveLocalChurchId($item['local_church'] ?? null);

                $ministry = Ministry::whereRaw(
                    'LOWER(name) = ? AND local_church_id <=> ?',
                    [strtolower(trim($item['name'])), $churchId]
                )->first();

                if (! $ministry) {
                    $ministry = Ministry::create([
                        'name' => trim($item['name']),
                        'local_church_id' => $churchId,
                    ]);
                }

                $member->ministries()->syncWithoutDetaching($ministry->id);
            }

            // Attach cluster groups
            foreach ($invitation->cluster_groups ?? [] as $item) {
                if (empty($item['name'])) {
                    continue;
                }

                $churchId = $this->resolveLocalChurchId($item['local_church']);

                $cluster = Cluster::whereRaw(
                    'LOWER(name) = ? AND local_church_id <=> ?',
                    [strtolower(trim($item['name'])), $churchId]
                )->first();

                if (! $cluster) {
                    $cluster = Cluster::create([
                        'name' => trim($item['name']),
                        'local_church_id' => $churchId,
                    ]);
                }

                $member->clusters()->syncWithoutDetaching($cluster->id);
            }

            $invitation->update([
                'accepted_at' => now(),
            ]);
        });

        return response()->json([
            'message' => 'Account created successfully and is awaiting approval.',
        ]);
    }

    protected function resolveLocalChurchId(?string $name): ?int
    {
        if (blank($name)) {
            return null;
        }

        $name = trim($name);

        $localChurch = LocalChurch::whereRaw(
            'LOWER(name) = ?',
            [strtolower($name)]
        )->first();

        if (! $localChurch) {
            $localChurch = LocalChurch::create([
                'name' => $name,
            ]);
        }

        return $localChurch->id;
    }

    protected function generateUsername(string $name): string
    {
        $base = Str::of($name)
            ->lower()
            ->ascii()
            ->replaceMatches('/[^a-z0-9]+/', '')
            ->toString();

        $username = $base;
        $counter = 2;

        while (User::where('username', $username)->exists()) {
            $username = "{$base}{$counter}";
            $counter++;
        }

        return $username;
    }
}
