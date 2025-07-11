<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgRealisationTache\Controllers\CommentaireRealisationTacheController;

// routes for commentaireRealisationTache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgRealisationTache')->group(function () {
        Route::get('commentaireRealisationTaches/getData', [CommentaireRealisationTacheController::class, 'getData'])->name('commentaireRealisationTaches.getData');
        // bulk - edit and delete
        Route::post('commentaireRealisationTaches/bulk-delete', [CommentaireRealisationTacheController::class, 'bulkDelete'])
        ->name('commentaireRealisationTaches.bulkDelete');
        Route::get('commentaireRealisationTaches/bulk-edit', [CommentaireRealisationTacheController::class, 'bulkEditForm'])
        ->name('commentaireRealisationTaches.bulkEdit');
        Route::post('commentaireRealisationTaches/bulk-update', [CommentaireRealisationTacheController::class, 'bulkUpdate'])
        ->name('commentaireRealisationTaches.bulkUpdate');

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
        Route::post('commentaireRealisationTaches/update-attributes', [CommentaireRealisationTacheController::class, 'updateAttributes'])->name('commentaireRealisationTaches.updateAttributes');

    

    });
});
