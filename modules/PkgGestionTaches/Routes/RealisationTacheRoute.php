<?php
// Ce fichier est maintenu par ESSARRAJ bulk-edit



use Illuminate\Support\Facades\Route;
use Modules\PkgGestionTaches\Controllers\RealisationTacheController;

// routes for realisationTache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGestionTaches')->group(function () {

        Route::get('realisationTaches/getData', [RealisationTacheController::class, 'getData'])->name('realisationTaches.getData');
        Route::resource('realisationTaches', RealisationTacheController::class)
            ->parameters(['realisationTaches' => 'realisationTache']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('realisationTaches/import', [RealisationTacheController::class, 'import'])->name('realisationTaches.import');
            Route::get('realisationTaches/export/{format}', [RealisationTacheController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('realisationTaches.export');

        });

        Route::post('realisationTaches/data-calcul', [RealisationTacheController::class, 'dataCalcul'])->name('realisationTaches.dataCalcul');
        Route::post('realisationTaches/update-attributes', [RealisationTacheController::class, 'updateAttributes'])->name('realisationTaches.updateAttributes');

        // bulk - edit and delete
        Route::post('realisationTaches/bulk-delete', [RealisationTacheController::class, 'bulkDelete'])
        ->name('realisationTaches.bulkDelete');
        Route::get('realisationTaches/bulk-edit', [RealisationTacheController::class, 'bulkEditForm'])
        ->name('realisationTaches.bulkEdit');
        // ✅ Route pour soumission des modifications en masse
        Route::post('realisationTaches/bulk-update', [RealisationTacheController::class, 'bulkUpdate'])
        ->name('realisationTaches.bulkUpdate');

    });
});
