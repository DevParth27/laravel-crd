<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;

// API Routes
Route::prefix('v1')->group(function () {
    // Posts endpoints
    Route::get('/posts', [PostController::class, 'index']);
});