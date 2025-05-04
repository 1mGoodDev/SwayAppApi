<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CommentController;
use App\Http\Controllers\api\LikeController;
use App\Http\Controllers\api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('post', PostController::class);
    Route::get('post-by-following', [PostController::class, 'following']);

    Route::post('post/{id}/like', [LikeController::class, 'like']);
    Route::post('post/{id}/comment', [CommentController::class, 'storeComment']);
    Route::get('post/{id}/comments', [CommentController::class, 'getComments']);

    Route::post('logout', [AuthController::class, 'logout']);
});
