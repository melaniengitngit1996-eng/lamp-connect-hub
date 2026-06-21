<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    $request->session()->regenerate();

    return response()->json([
        'user' => Auth::user()
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
