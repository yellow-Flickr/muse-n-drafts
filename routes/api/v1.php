<?php

use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\PostController;
use App\Http\Controllers\API\V1\TagController;
use Illuminate\Support\Facades\Route;

Route::apiResource('posts', PostController::class)->except(['replace','update']);
Route::patch('posts/{post}', [PostController::class, 'update']);
Route::put('posts/{post}', [PostController::class, 'replace']);

Route::apiResource('categories', CategoryController::class);

Route::apiResource('tags', TagController::class);
