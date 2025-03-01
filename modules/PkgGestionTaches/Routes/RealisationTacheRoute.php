<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGestionTaches\Controllers\RealisationTacheController;

// routes for realisationTache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGestionTaches')->group(function () {

        Route::get('realisationTaches/getRealisationTaches', [RealisationTacheController::class, 'getRealisationTaches'])->name('realisationTaches.all');
        Route::resource('realisationTaches', RealisationTacheController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('realisationTaches/import', [RealisationTacheController::class, 'import'])->name('realisationTaches.import');
            Route::get('realisationTaches/export/{format}', [RealisationTacheController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('realisationTaches.export');

        });

        Route::post('realisationTaches/data-calcul', [RealisationTacheController::class, 'dataCalcul'])->name('realisationTaches.dataCalcul');

    });
});
