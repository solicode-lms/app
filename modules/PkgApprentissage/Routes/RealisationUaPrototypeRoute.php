<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgApprentissage\Controllers\RealisationUaPrototypeController;

// routes for realisationUaPrototype management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgApprentissage')->group(function () {
        Route::get('realisationUaPrototypes/getData', [RealisationUaPrototypeController::class, 'getData'])->name('realisationUaPrototypes.getData');
        // ✅ Route JSON
        Route::get('realisationUaPrototypes/json/{id}', [RealisationUaPrototypeController::class, 'getRealisationUaPrototype'])
            ->name('realisationUaPrototypes.getById');
        // bulk - edit and delete
        Route::post('realisationUaPrototypes/bulk-delete', [RealisationUaPrototypeController::class, 'bulkDelete'])
        ->name('realisationUaPrototypes.bulkDelete');
        Route::get('realisationUaPrototypes/bulk-edit', [RealisationUaPrototypeController::class, 'bulkEditForm'])
        ->name('realisationUaPrototypes.bulkEdit');
        Route::post('realisationUaPrototypes/bulk-update', [RealisationUaPrototypeController::class, 'bulkUpdate'])
        ->name('realisationUaPrototypes.bulkUpdate');

        Route::resource('realisationUaPrototypes', RealisationUaPrototypeController::class)
            ->parameters(['realisationUaPrototypes' => 'realisationUaPrototype']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('realisationUaPrototypes/import', [RealisationUaPrototypeController::class, 'import'])->name('realisationUaPrototypes.import');
            Route::get('realisationUaPrototypes/export/{format}', [RealisationUaPrototypeController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('realisationUaPrototypes.export');

        });

        Route::post('realisationUaPrototypes/data-calcul', [RealisationUaPrototypeController::class, 'dataCalcul'])->name('realisationUaPrototypes.dataCalcul');
        Route::post('realisationUaPrototypes/update-attributes', [RealisationUaPrototypeController::class, 'updateAttributes'])->name('realisationUaPrototypes.updateAttributes');

    

    });
});
