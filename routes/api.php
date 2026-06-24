<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileFolderController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderPermissionController;
use App\Http\Controllers\FilePermissionController;
use App\Http\Controllers\SharedDriveController;

Route::get('/drive/folders', [FileFolderController::class, 'index']);

Route::post('/drive/folders', [FileFolderController::class, 'store']);

Route::delete('/drive/folders/{folder}', [FileFolderController::class, 'destroy']);

Route::post('/drive/folders/{folder}/share', [FileFolderController::class, 'share']);

Route::post('/drive/files', [FileController::class, 'store']);

Route::delete('/drive/files/{file}', [FileController::class, 'destroy']);

Route::get('/drive/folders/{folder}/permissions', [FolderPermissionController::class, 'index']);

Route::post('/drive/folders/{folder}/permissions', [FolderPermissionController::class, 'store']);

Route::patch('/drive/folders/{folder}/visibility', [FolderPermissionController::class, 'updateVisibility']);

Route::patch('/drive/folder-permissions/{permission}', [FolderPermissionController::class, 'update']);

Route::delete('/drive/folder-permissions/{permission}', [FolderPermissionController::class, 'destroy']);

Route::get('/drive/files/{file}/permissions', [FilePermissionController::class, 'index']);

Route::post('/drive/files/{file}/permissions', [FilePermissionController::class, 'store']);

Route::patch('/drive/files/{file}/visibility', [FilePermissionController::class, 'updateVisibility']);

Route::patch('/drive/file-permissions/{permission}', [FilePermissionController::class, 'update']);

Route::delete('/drive/file-permissions/{permission}', [FilePermissionController::class, 'destroy']);

Route::get('/drive/share-search', [FolderPermissionController::class, 'search']);

Route::get(
    '/shared/folders/{token}',
    [SharedDriveController::class, 'folder']
);

Route::get(
    '/shared/files/{token}',
    [SharedDriveController::class, 'file']
);
