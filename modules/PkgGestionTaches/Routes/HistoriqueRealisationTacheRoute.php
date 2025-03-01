<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGestionTaches\Controllers\HistoriqueRealisationTacheController;

// routes for historiqueRealisationTache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGestionTaches')->group(function () {

        Route::get('historiqueRealisationTaches/getHistoriqueRealisationTaches', [HistoriqueRealisationTacheController::class, 'getHistoriqueRealisationTaches'])->name('historiqueRealisationTaches.all');
        Route::resource('historiqueRealisationTaches', HistoriqueRealisationTacheController::class)
            ->parameters(['historiqueRealisationTaches' => 'historiqueRealisationTache']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('historiqueRealisationTaches/import', [HistoriqueRealisationTacheController::class, 'import'])->name('historiqueRealisationTaches.import');
            Route::get('historiqueRealisationTaches/export/{format}', [HistoriqueRealisationTacheController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('historiqueRealisationTaches.export');

        });

        Route::post('historiqueRealisationTaches/data-calcul', [HistoriqueRealisationTacheController::class, 'dataCalcul'])->name('historiqueRealisationTaches.dataCalcul');

    });
});
