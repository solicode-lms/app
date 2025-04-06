<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGestionTaches\Controllers\EtatRealisationTacheController;

// routes for etatRealisationTache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGestionTaches')->group(function () {

        Route::get('etatRealisationTaches/getData', [EtatRealisationTacheController::class, 'getData'])->name('etatRealisationTaches.getData');
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
