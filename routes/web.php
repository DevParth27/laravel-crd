<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ApiFetchController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApiDataController;
use App\Http\Controllers\ProfileController;


// Redirect root to excel dashboard
Route::get('/', function () {
    return redirect()->route('excel.index');
});

// Login and Logout (public routes)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Group protected routes that require authentication
Route::middleware(['auth'])->group(function () {
    // Routes accessible by both users and admins
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update-name', [ProfileController::class, 'updateName'])->name('profile.updateName');
    Route::post('/profile/update-email', [ProfileController::class, 'updateEmail'])->name('profile.updateEmail');
    Route::post('/profile/reset-password', [ProfileController::class, 'resetPassword'])->name('profile.resetPassword');

    // Excel routes (read-only for users)
    Route::get('/excel', [ExcelController::class, 'index'])->name('excel.index');
    Route::get('/excel/files/datatables', [ExcelController::class, 'filesData'])->name('excel.files.data');
    
    // Posts routes (read-only for users)
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    
    // Admin only routes
    Route::middleware(['role:admin'])->group(function () {
        // Excel admin routes
        Route::get('/excel/upload', [ExcelController::class, 'upload'])->name('excel.upload');
        Route::post('/excel/store', [ExcelController::class, 'store'])->name('excel.store');
        
        // Posts admin routes
        Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
        Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
        Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
        Route::delete('/excel/{fileName}/delete', [ExcelController::class, 'deleteFile'])->name('excel.delete-file');
        
        // API Data Routes 
        Route::get('/api-data', [ApiDataController::class, 'index'])->name('api.index');
        Route::post('/api-data/fetch', [ApiDataController::class, 'fetchData'])->name('api.fetch');
        Route::get('/api-data/datatable', [ApiDataController::class, 'getDatatableData'])->name('api.datatable');
        Route::get('/api-data/columns', [ApiDataController::class, 'getColumns'])->name('api.columns');
    });
    
    Route::get('/excel/{fileName}/data', [ExcelController::class, 'getData'])->name('excel.data');
    Route::get('/excel/{fileName}/columns', [ExcelController::class, 'getColumns'])->name('excel.columns');
    Route::get('/excel/{file}', [ExcelController::class, 'show'])->name('excel.show');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
});