<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgRealisationTache\Controllers\HistoriqueRealisationTacheController;

// routes for historiqueRealisationTache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgRealisationTache')->group(function () {
        Route::get('historiqueRealisationTaches/getData', [HistoriqueRealisationTacheController::class, 'getData'])->name('historiqueRealisationTaches.getData');
        // ✅ Route JSON
        Route::get('historiqueRealisationTaches/json/{id}', [HistoriqueRealisationTacheController::class, 'getHistoriqueRealisationTache'])
            ->name('historiqueRealisationTaches.getById');
        // bulk - edit and delete
        Route::post('historiqueRealisationTaches/bulk-delete', [HistoriqueRealisationTacheController::class, 'bulkDelete'])
        ->name('historiqueRealisationTaches.bulkDelete');
        Route::get('historiqueRealisationTaches/bulk-edit', [HistoriqueRealisationTacheController::class, 'bulkEditForm'])
        ->name('historiqueRealisationTaches.bulkEdit');
        Route::post('historiqueRealisationTaches/bulk-update', [HistoriqueRealisationTacheController::class, 'bulkUpdate'])
        ->name('historiqueRealisationTaches.bulkUpdate');

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
        Route::post('historiqueRealisationTaches/update-attributes', [HistoriqueRealisationTacheController::class, 'updateAttributes'])->name('historiqueRealisationTaches.updateAttributes');

    

    });
});
