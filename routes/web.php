<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

/*
|--------------------------------------------------------------------------
| API / AUTH ROUTES (MUST BE FIRST)
|--------------------------------------------------------------------------
*/

Route::get('/debug-session-write', function (Request $request) {
    session(['test' => 'hello']);

    return [
        'id' => session()->getId(),
        'test' => session('test'),
        'user' => auth()->user(),
    ];
});

Route::get('/debug-session-read', function () {
    return [
        'id' => session()->getId(),
        'test' => session('test'),
        'user' => auth()->user(),
    ];
});

Route::get('/me', function () {
    $user = Auth::user();

    if (! $user) {
        return response()->json([
            'message' => 'Unauthenticated',
        ], 401);
    }

    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'roles' => $user->getRoleNames(),
        'permissions' => $user->getAllPermissions()
            ->pluck('name')
            ->values(),
    ]);
});

Route::post('/login', function (Request $request) {
    $validated = $request->validate([
        'login' => ['required'],
        'password' => ['required'],
    ]);

    $user = User::whereRaw(
        'LOWER(email) = ?',
        [strtolower($validated['login'])]
    )
        ->orWhereRaw(
            'LOWER(username) = ?',
            [strtolower($validated['login'])]
        )
        ->first();

    if (! $user || ! Hash::check($validated['password'], $user->password)) {
        return response()->json([
            'code' => 'INVALID_CREDENTIALS',
            'message' => 'Invalid credentials',
        ], 401);
    }

    if ($user->status === 'pending') {
        return response()->json([
            'code' => 'PENDING_APPROVAL',
            'message' => 'Your account is awaiting approval.',
        ], 403);
    }

    if ($user->status === 'rejected') {
        return response()->json([
            'code' => 'ACCOUNT_REJECTED',
            'message' => 'Your account has been rejected.',
        ], 403);
    }

    Auth::login($user);

    $request->session()->regenerate();

    return response()->json([
        'user' => $user,
    ]);
});

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json([
        'message' => 'Logged out successfully'
    ]);
});

Route::post('/forgot-password', function (Request $request) {
    $validated = $request->validate([
        'email' => ['required', 'email'],
    ]);

    $status = Password::sendResetLink([
        'email' => $validated['email'],
    ]);

    if ($status !== Password::RESET_LINK_SENT) {
        return response()->json([
            'code' => 'RESET_LINK_FAILED',
            'message' => __($status),
        ], 422);
    }

    return response()->json([
        'message' => 'Password reset link sent successfully.',
    ]);
});

Route::post('/reset-password', function (Request $request) {
    $validated = $request->validate([
        'token' => ['required'],
        'email' => ['required', 'email'],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);

    $status = Password::reset(
        $validated,
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->save();
        }
    );

    if ($status !== Password::PASSWORD_RESET) {
        return response()->json([
            'code' => 'PASSWORD_RESET_FAILED',
            'message' => __($status),
        ], 422);
    }

    return response()->json([
        'message' => 'Your password has been reset successfully.',
    ]);
});

/*
|--------------------------------------------------------------------------
| FRONTEND CATCH-ALL (LAST!)
|--------------------------------------------------------------------------
*/

Route::get('/{any?}', function () {
    return view('welcome');
})->where('any', '.*');
