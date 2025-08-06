<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgRealisationTache\Controllers\EtatRealisationTacheController;

// routes for etatRealisationTache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgRealisationTache')->group(function () {
        Route::get('etatRealisationTaches/getData', [EtatRealisationTacheController::class, 'getData'])->name('etatRealisationTaches.getData');
        // ✅ Route JSON
        Route::get('etatRealisationTaches/json/{id}', [EtatRealisationTacheController::class, 'getEtatRealisationTache'])
            ->name('etatRealisationTaches.getById');
        // bulk - edit and delete
        Route::post('etatRealisationTaches/bulk-delete', [EtatRealisationTacheController::class, 'bulkDelete'])
        ->name('etatRealisationTaches.bulkDelete');
        Route::get('etatRealisationTaches/bulk-edit', [EtatRealisationTacheController::class, 'bulkEditForm'])
        ->name('etatRealisationTaches.bulkEdit');
        Route::post('etatRealisationTaches/bulk-update', [EtatRealisationTacheController::class, 'bulkUpdate'])
        ->name('etatRealisationTaches.bulkUpdate');

        Route::resource('etatRealisationTaches', EtatRealisationTacheController::class)
            ->parameters(['etatRealisationTaches' => 'etatRealisationTache']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('etatRealisationTaches/import', [EtatRealisationTacheController::class, 'import'])->name('etatRealisationTaches.import');
            Route::get('etatRealisationTaches/export/{format}', [EtatRealisationTacheController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('etatRealisationTaches.export');

        });

        Route::post('etatRealisationTaches/data-calcul', [EtatRealisationTacheController::class, 'dataCalcul'])->name('etatRealisationTaches.dataCalcul');
        Route::post('etatRealisationTaches/update-attributes', [EtatRealisationTacheController::class, 'updateAttributes'])->name('etatRealisationTaches.updateAttributes');

    

    });
});
