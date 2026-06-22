<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileFolderController;
use App\Http\Controllers\FileController;

Route::get('/drive/folders', [FileFolderController::class, 'index']);

Route::post('/drive/folders', [FileFolderController::class, 'store']);

Route::delete('/drive/folders/{folder}', [FileFolderController::class, 'destroy']);

Route::post('/drive/files', [FileController::class, 'store']);

Route::delete('/drive/files/{file}', [FileController::class, 'destroy']);
