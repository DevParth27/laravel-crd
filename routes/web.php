<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ApiFetchController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApiDataController; // Add this import

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
    // Excel routes
    
    Route::get('/excel', [ExcelController::class, 'index'])->name('excel.index');
    Route::get('/excel/files/datatables', [ExcelController::class, 'filesData'])->name('excel.files.data');
    Route::get('/excel/upload', [ExcelController::class, 'upload'])->name('excel.upload');
    Route::post('/excel/store', [ExcelController::class, 'store'])->name('excel.store');
    Route::get('/excel/{fileName}/data', [ExcelController::class, 'getData'])->name('excel.data');
    Route::get('/excel/{fileName}/columns', [ExcelController::class, 'getColumns'])->name('excel.columns');
    Route::get('/excel/{file}', [ExcelController::class, 'show'])->name('excel.show');
    Route::delete('/excel/{fileName}/delete', [ExcelController::class, 'deleteFile'])->name('excel.delete-file');
    
    // Posts CRUD routes
    Route::resource('posts', PostController::class);
    
    // Dynamic API Data Routes (NEW)
    Route::get('/api-data', [ApiDataController::class, 'index'])->name('api.index');
    Route::post('/api-data/fetch', [ApiDataController::class, 'fetchData'])->name('api.fetch');
    Route::get('/api-data/datatable', [ApiDataController::class, 'getDatatableData'])->name('api.datatable');
    Route::get('/api-data/columns', [ApiDataController::class, 'getColumns'])->name('api.columns');
    
    // Original API fetch routes (commented out - you can remove these if not needed)
    // Route::get('/api-fetch', [ApiFetchController::class, 'form'])->name('fetch.api.form');
    // Route::post('/api-fetch', [ApiFetchController::class, 'fetch'])->name('fetch.api.fetch');
    // Route::get('/api-fetch/data', [ApiFetchController::class, 'getTableData'])->name('fetch.api.get_table_data');
});