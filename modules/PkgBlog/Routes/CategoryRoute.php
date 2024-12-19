<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgBlog\Controllers\CategoryController;

// routes for category management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgBlog')->group(function () {

        Route::get('categories/getCategories', [CategoryController::class, 'getCategories'])->name('categories.all');
        Route::resource('categories', CategoryController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('categories/export', [CategoryController::class, 'export'])->name('categories.export');
            Route::post('categories/import', [CategoryController::class, 'import'])->name('categories.import');
        });
    });
});
