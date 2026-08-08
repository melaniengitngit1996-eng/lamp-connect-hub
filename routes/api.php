<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\CompositionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileFolderController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderPermissionController;
use App\Http\Controllers\FilePermissionController;
use App\Http\Controllers\SharedDriveController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocalChurchController;
use App\Http\Controllers\MinistryController;
use App\Http\Controllers\ClusterGroupController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TestimonyController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\ChatController;


Route::get('/drive/folders', [FileFolderController::class, 'index']);

Route::post('/drive/folders', [FileFolderController::class, 'store']);

Route::delete('/drive/folders/{folder}', [FileFolderController::class, 'destroy']);

Route::post('/drive/folders/{folder}/share', [FileFolderController::class, 'share']);

Route::patch('/drive/folders/{folder}/move', [FileFolderController::class, 'move']);

Route::post('/drive/files', [FileController::class, 'store']);

Route::delete('/drive/files/{file}', [FileController::class, 'destroy']);

Route::patch('/drive/files/{file}/move', [FileController::class, 'move']);

Route::get('/drive/folders/{folder}/permissions', [FolderPermissionController::class, 'index']);

Route::post('/drive/folders/{folder}/permissions', [FolderPermissionController::class, 'store']);

Route::patch('/drive/folders/{folder}/visibility', [FolderPermissionController::class, 'updateVisibility']);

Route::patch('/drive/folders/{folder}', [FileFolderController::class, 'update']);

Route::post('/drive/folders/{folder}/favorite', [FileFolderController::class, 'toggleFavorite']);

Route::patch('/drive/folder-permissions/{permission}', [FolderPermissionController::class, 'update']);

Route::delete('/drive/folder-permissions/{permission}', [FolderPermissionController::class, 'destroy']);

Route::get('/drive/files/{file}/permissions', [FilePermissionController::class, 'index']);

Route::post('/drive/files/{file}/permissions', [FilePermissionController::class, 'store']);

Route::patch('/drive/files/{file}/visibility', [FilePermissionController::class, 'updateVisibility']);

Route::patch('/drive/file-permissions/{permission}', [FilePermissionController::class, 'update']);

Route::delete('/drive/file-permissions/{permission}', [FilePermissionController::class, 'destroy']);

Route::get('/drive/share-search', [FolderPermissionController::class, 'search']);

Route::get('/shared/folders/{token}', [SharedDriveController::class, 'folder']);

Route::get('/shared/files/{token}', [SharedDriveController::class, 'file']);

Route::get('/invitations', [InvitationController::class, 'index']);

Route::post('/invitations', [InvitationController::class, 'store']);

Route::get('/invitations/{token}', [InvitationController::class, 'show']);

Route::post('/invitations/{token}/signup', [InvitationController::class, 'signup']);

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/pending', [UserController::class, 'pending']);
    Route::post('/{user}/approve', [UserController::class, 'approve']);
    Route::post('/{user}/reject', [UserController::class, 'reject']);
    Route::delete('/{user}', [UserController::class, 'destroy']);
    Route::post('/', [UserController::class, 'store']);
    Route::put('/{user}', [UserController::class, 'update']);
});

Route::get('/roles', [RoleController::class, 'index']);
Route::post('/roles', [RoleController::class, 'store']);
Route::put('/roles/{role}', [RoleController::class, 'update']);
Route::delete('/roles/{role}', [RoleController::class, 'destroy']);

Route::get('/permissions', [PermissionController::class, 'index']);

Route::get('/local-churches', [LocalChurchController::class, 'index']);
Route::delete('/local-churches/{localChurch}', [LocalChurchController::class, 'destroy']);
Route::post('/local-churches', [LocalChurchController::class, 'store']);
Route::put('/local-churches/{localChurch}', [LocalChurchController::class, 'update']);

Route::get('/ministries', [MinistryController::class, 'all']);
Route::delete('/ministries/{ministry}', [MinistryController::class, 'destroy']);
Route::post('/ministries', [MinistryController::class, 'store']);
Route::put('/ministries/{ministry}', [MinistryController::class, 'update']);

Route::get('/clusters', [ClusterGroupController::class, 'all']);
Route::post('/clusters', [ClusterGroupController::class, 'store']);
Route::put('/clusters/{cluster}', [ClusterGroupController::class, 'update']);
Route::delete('/clusters/{cluster}', [ClusterGroupController::class, 'destroy']);

Route::get('/local-churches/{localChurch}/ministries', [MinistryController::class, 'index']);
Route::get('/local-churches/{localChurch}/clusters', [ClusterGroupController::class, 'index']);

Route::post('/drive/files/{file}/activities/view', [FileController::class, 'logView']);
Route::post('/drive/files/{file}/activities/download', [FileController::class, 'logDownload']);
Route::get('/drive/files/{file}/activities', [FileController::class, 'activities']);
Route::patch('/drive/files/{file}', [FileController::class, 'update']);
Route::post('/drive/files/{file}/favorite', [FileController::class, 'toggleFavorite']);

Route::get('/events/highlights', [EventController::class, 'highlights']);
Route::get('/events', [EventController::class, 'index']);
Route::post('/events', [EventController::class, 'store']);
Route::put('/events/{event}', [EventController::class, 'update']);
Route::delete('/events/{event}', [EventController::class, 'destroy']);

Route::get('/announcements/latest', [AnnouncementController::class, 'latest']);
Route::get('/announcements', [AnnouncementController::class, 'index']);
Route::post('/announcements', [AnnouncementController::class, 'store']);
Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update']);
Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy']);

Route::get('/compositions/latest', [CompositionController::class, 'latest']);
Route::get('/compositions', [CompositionController::class, 'index']);
Route::post('/compositions', [CompositionController::class, 'store']);
Route::put('/compositions/{composition}', [CompositionController::class, 'update']);
Route::delete('/compositions/{composition}', [CompositionController::class, 'destroy']);

Route::get('/testimonies/latest', [TestimonyController::class, 'latest']);
Route::get('/testimonies', [TestimonyController::class, 'index']);
Route::post('/testimonies', [TestimonyController::class, 'store']);
Route::put('/testimonies/{testimony}', [TestimonyController::class, 'update']);
Route::delete('/testimonies/{testimony}', [TestimonyController::class, 'destroy']);

Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);
Route::delete('/posts/{post}', [PostController::class, 'destroy']);

Route::post('/posts/{post}/like', [PostController::class, 'toggleLike']);

Route::get('/posts/{post}/comments', [PostCommentController::class, 'index']);
Route::post('/posts/{post}/comments', [PostCommentController::class, 'store']);
Route::delete('/comments/{comment}', [PostCommentController::class, 'destroy']);

// Sidebar
Route::get('/chat', [ChatController::class, 'index']);
// Load conversation
Route::get('/chat/conversations/{conversation}', [ChatController::class, 'show']);
// Create
Route::post('/chat/groups', [ChatController::class, 'storeGroup']);
Route::post('/chat/direct', [ChatController::class, 'storeDirect']);
// Messages
Route::post('/chat/conversations/{conversation}/messages', [ChatController::class, 'sendMessage']);
// Delete message
Route::delete('/chat/messages/{message}', [ChatController::class, 'destroyMessage']);
// Members
Route::post('/chat/conversations/{conversation}/members', [ChatController::class, 'addMember']);
Route::delete('/chat/conversations/{conversation}/members/{user}', [ChatController::class, 'removeMember']);
Route::get('/chat/users', [ChatController::class, 'getUsers']);
Route::get('/chat/conversations/{conversation}/available-users', [ChatController::class, 'availableUsers']);
