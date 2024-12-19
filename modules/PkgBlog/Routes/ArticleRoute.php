<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgBlog\Controllers\ArticleController;

// routes for article management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgBlog')->group(function () {

        Route::get('articles/getArticles', [ArticleController::class, 'getArticles'])->name('articles.all');
        Route::resource('articles', ArticleController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('articles/export', [ArticleController::class, 'export'])->name('articles.export');
            Route::post('articles/import', [ArticleController::class, 'import'])->name('articles.import');
        });
    });
});
