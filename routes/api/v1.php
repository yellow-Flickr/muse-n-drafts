<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\PostController;
use App\Http\Controllers\API\V1\TagController;
use App\Permissions\V1\TokenAbilities;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->group();

Route::middleware(['throttle:auth'])->post('/login', [AuthController::class, 'login']);
Route::middleware(['throttle:auth'])->post('/register', [AuthController::class, 'register']);

Route::get('posts/{post}', [PostController::class, 'show']);
Route::get('posts', [PostController::class, 'index']);
Route::get('categories', [CategoryController::class, 'index']);
Route::get('tags', [TagController::class, 'index']);
// Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->group(function () {
    // Route::apiResource('posts', PostController::class)->except(['replace', 'update']);

    Route::get('categories/{category}', [CategoryController::class, 'show']);
    
    Route::middleware(['throttle:crud'])->group(function () {
        Route::middleware('abilities:'.TokenAbilities::CreatePost)->post('posts', [PostController::class, 'store']);
        Route::middleware('abilities:'.TokenAbilities::DeletePost)->delete('posts/{post}', [PostController::class, 'destroy']);
        Route::middleware('abilities:'.TokenAbilities::UpdatePost)->patch('posts/{post}', [PostController::class, 'update']);
        Route::middleware('abilities:'.TokenAbilities::ReplacePost)->put('posts/{post}', [PostController::class, 'replace']);

        Route::middleware('abilities:'.TokenAbilities::CreateCategory)->post('categories', [CategoryController::class, 'store']);
        Route::middleware('abilities:'.TokenAbilities::DeleteCategory)->delete('categories/{category}', [CategoryController::class, 'destroy']);
        Route::middleware('abilities:'.TokenAbilities::UpdateCategory)->patch('categories/{category}', [CategoryController::class, 'update']);
        Route::middleware('abilities:'.TokenAbilities::ReplaceCategory)->put('categories/{category}', [CategoryController::class, 'replace']);

        Route::middleware('abilities:'.TokenAbilities::CreateTag)->post('tags', [TagController::class, 'store']);
        Route::middleware('abilities:'.TokenAbilities::DeleteTag)->delete('tags/{tag}', [TagController::class, 'destroy']);
        Route::middleware('abilities:'.TokenAbilities::UpdateTag)->patch('tags/{tag}', [TagController::class, 'update']);
        Route::middleware('abilities:'.TokenAbilities::ReplaceTag)->put('tags/{tag}', [TagController::class, 'replace']);
    });

    Route::get('tags/{tag}', [TagController::class, 'show']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
