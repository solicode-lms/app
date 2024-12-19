<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgBlog\Controllers\CommentController;

// routes for comment management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgBlog')->group(function () {

        Route::get('comments/getComments', [CommentController::class, 'getComments'])->name('comments.all');
        Route::resource('comments', CommentController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('comments/export', [CommentController::class, 'export'])->name('comments.export');
            Route::post('comments/import', [CommentController::class, 'import'])->name('comments.import');
        });
    });
});
