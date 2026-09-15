<?php

use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\PostController;
use App\Http\Controllers\API\V1\TagController;
use Illuminate\Support\Facades\Route;

Route::apiResource('posts', PostController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('tags', TagController::class);




    