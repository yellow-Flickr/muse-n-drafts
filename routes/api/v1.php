<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\PostController;
use App\Http\Controllers\API\V1\TagController;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->group();

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('posts/{post}', [PostController::class, 'show']);
Route::get('posts', [PostController::class, 'index']);
// Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->group(function () {
    // Route::apiResource('posts', PostController::class)->except(['replace', 'update']);
    Route::delete('posts/{post}', [PostController::class, 'destroy']);
    Route::patch('posts/{post}', [PostController::class, 'update']);
    Route::put('posts/{post}', [PostController::class, 'replace']);

    Route::apiResource('categories', CategoryController::class);

    Route::apiResource('tags', TagController::class);
});
