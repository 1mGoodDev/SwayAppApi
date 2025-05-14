<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CommentController;
use App\Http\Controllers\api\FollowController;
use App\Http\Controllers\api\LikeController;
use App\Http\Controllers\api\NotificationController;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('feed', PostController::class);
    Route::get('feed-by-following', [PostController::class, 'following']);
    Route::get('admin-info', [NotificationController::class, 'getAdminInfo']);

    Route::post('post/{id}/like', [LikeController::class, 'like']);
    Route::post('post/{id}/comment', [CommentController::class, 'storeComment']);
    Route::get('post/{id}/comments', [CommentController::class, 'getComments']);

    Route::get('my-profile', [ProfileController::class, 'myProfile']);
    Route::put('my-profile/update', [ProfileController::class, 'updateProfile']);
    Route::post('my-profile/update/photo', [ProfileController::class, 'updatePhotoProfile']);
    Route::post('my-profile/update/background-img', [ProfileController::class, 'updateBackgroundImage']);
    Route::get('profile/{name}', [ProfileController::class, 'otherProfile']);

    Route::post('follow/{id}', [FollowController::class, 'follow']);
    Route::post('unfollow/{id}', [FollowController::class, 'unfollow']);

    Route::post('logout', [AuthController::class, 'logout']);
});
