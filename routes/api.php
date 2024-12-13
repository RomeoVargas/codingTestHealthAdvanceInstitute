<?php

use App\Http\Controllers\API\v1\AuthController;
use App\Http\Controllers\API\v1\PostController;
use App\Http\Controllers\API\v1\UserController;
use App\Http\Controllers\API\v1\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/logout', [AuthController::class, 'logout']);

        Route::get('/tags', [TagController::class, 'index']);

        Route::prefix('posts')->group(function () {
            Route::get('/', [PostController::class, 'index']);
            Route::post('/create', [PostController::class, 'create']);
            Route::put('/update/{userId}/{postId}', [PostController::class, 'update']);
            Route::delete('/delete/{userId}/{postId}', [PostController::class, 'delete']);
        });
    });
});
