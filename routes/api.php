<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ExcelController;

// // API Routes
// Route::prefix('v1')->group(function () {
//     // Posts endpoints
//     Route::get('/posts', [PostController::class, 'index']);
    
//     // Excel endpoints
//     Route::get('/excel', [ExcelController::class, 'index']);
//     Route::get('/excel/{fileName}', [ExcelController::class, 'show']);
//     Route::get('/excel/{fileName}/columns', [ExcelController::class, 'getColumns']);
// });

// Route::middleware('auth:api')->get('/user', function(Request $request) {
//     return $request->user();
// });

// Route::apiResource('posts', PostController::class)
//     ->only(['index', 'show' , 'update', 'destroy']);

// for register and login 
Route::post('login', [App\Http\Controllers\Api\LoginApiController::class, '__invoke'])->name('login');
Route::post('register', [App\Http\Controllers\Api\RegisterApiController::class, '__invoke'])->name('register');
Route::group([
    'prefix' => 'v1',
    'middleware' => ['auth:sanctum'],
], function () {
    Route::apiResource('posts', PostController::class);
});
