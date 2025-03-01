<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGestionTaches\Controllers\CommentaireRealisationTacheController;

// routes for commentaireRealisationTache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGestionTaches')->group(function () {

        Route::get('commentaireRealisationTaches/getCommentaireRealisationTaches', [CommentaireRealisationTacheController::class, 'getCommentaireRealisationTaches'])->name('commentaireRealisationTaches.all');
        Route::resource('commentaireRealisationTaches', CommentaireRealisationTacheController::class)
            ->parameters(['commentaireRealisationTaches' => 'commentaireRealisationTache']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('commentaireRealisationTaches/import', [CommentaireRealisationTacheController::class, 'import'])->name('commentaireRealisationTaches.import');
            Route::get('commentaireRealisationTaches/export/{format}', [CommentaireRealisationTacheController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('commentaireRealisationTaches.export');

        });

        Route::post('commentaireRealisationTaches/data-calcul', [CommentaireRealisationTacheController::class, 'dataCalcul'])->name('commentaireRealisationTaches.dataCalcul');

    });
});
