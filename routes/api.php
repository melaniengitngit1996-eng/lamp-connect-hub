<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileFolderController;

Route::get('/drive/folders', [FileFolderController::class, 'index']);

Route::post('/drive/folders', [FileFolderController::class, 'store']);

Route::delete('/drive/folders/{folder}', [FileFolderController::class, 'destroy']);
