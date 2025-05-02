<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGestionTaches\Controllers\LabelRealisationTacheController;

// routes for labelRealisationTache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGestionTaches')->group(function () {
        Route::get('labelRealisationTaches/getData', [LabelRealisationTacheController::class, 'getData'])->name('labelRealisationTaches.getData');
        // bulk - edit and delete
        Route::post('labelRealisationTaches/bulk-delete', [LabelRealisationTacheController::class, 'bulkDelete'])
        ->name('labelRealisationTaches.bulkDelete');
        Route::get('labelRealisationTaches/bulk-edit', [LabelRealisationTacheController::class, 'bulkEditForm'])
        ->name('labelRealisationTaches.bulkEdit');
        Route::post('labelRealisationTaches/bulk-update', [LabelRealisationTacheController::class, 'bulkUpdate'])
        ->name('labelRealisationTaches.bulkUpdate');

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
