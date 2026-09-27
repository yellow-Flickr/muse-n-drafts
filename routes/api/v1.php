<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\PostController;
use App\Http\Controllers\API\V1\TagController;
use App\Permissions\V1\TokenAbilities;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->group();

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('posts/{post}', [PostController::class, 'show']);
Route::get('posts', [PostController::class, 'index']);
// Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->group(function () {
    // Route::apiResource('posts', PostController::class)->except(['replace', 'update']);
    Route::middleware('abilities:'.TokenAbilities::CreatePost)->post('posts', [PostController::class, 'store']);
    Route::middleware('abilities:'.TokenAbilities::DeletePost)->delete('posts/{post}', [PostController::class, 'destroy']);
    Route::middleware('abilities:'.TokenAbilities::UpdatePost)->patch('posts/{post}', [PostController::class, 'update']);
    Route::middleware('abilities:'.TokenAbilities::ReplacePost)->put('posts/{post}', [PostController::class, 'replace']);
    
    Route::apiResource('categories', CategoryController::class);
    
    Route::apiResource('tags', TagController::class);

    Route::post('/logout', [AuthController::class, 'logout']);
    });
