<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGestionTaches\Controllers\LabelRealisationTacheController;

// routes for labelRealisationTache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGestionTaches')->group(function () {

        Route::get('labelRealisationTaches/getData', [LabelRealisationTacheController::class, 'getData'])->name('labelRealisationTaches.getData');
        Route::resource('labelRealisationTaches', LabelRealisationTacheController::class)
            ->parameters(['labelRealisationTaches' => 'labelRealisationTache']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('labelRealisationTaches/import', [LabelRealisationTacheController::class, 'import'])->name('labelRealisationTaches.import');
            Route::get('labelRealisationTaches/export/{format}', [LabelRealisationTacheController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('labelRealisationTaches.export');

        });

        Route::post('labelRealisationTaches/data-calcul', [LabelRealisationTacheController::class, 'dataCalcul'])->name('labelRealisationTaches.dataCalcul');
        Route::post('labelRealisationTaches/update-attributes', [LabelRealisationTacheController::class, 'updateAttributes'])->name('labelRealisationTaches.updateAttributes');

    

    });
});
