<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
    if (!Auth::check()) {
        return response()->json([
            'message' => 'Unauthenticated'
        ], 401);
    }

    return response()->json(Auth::user());
});

Route::post('/login', function (Request $request) {
    $user = User::where(
        'email',
        $request->email
    )->first();

    if (! $user) {
        return response()->json([
            'code' => 'INVALID_CREDENTIALS',
            'message' => 'Invalid credentials',
        ], 401);
    }

    if (! Hash::check(
        $request->password,
        $user->password
    )) {
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
        'user' => Auth::user(),
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

/*
|--------------------------------------------------------------------------
| FRONTEND CATCH-ALL (LAST!)
|--------------------------------------------------------------------------
*/

Route::get('/{any?}', function () {
    return view('welcome');
})->where('any', '.*');
