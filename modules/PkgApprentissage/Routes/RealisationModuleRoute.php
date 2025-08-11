<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgApprentissage\Controllers\RealisationModuleController;

// routes for realisationModule management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgApprentissage')->group(function () {
        Route::get('realisationModules/getData', [RealisationModuleController::class, 'getData'])->name('realisationModules.getData');
        // ✅ Route JSON
        Route::get('realisationModules/json/{id}', [RealisationModuleController::class, 'getRealisationModule'])
            ->name('realisationModules.getById');
        // bulk - edit and delete
        Route::post('realisationModules/bulk-delete', [RealisationModuleController::class, 'bulkDelete'])
        ->name('realisationModules.bulkDelete');
        Route::get('realisationModules/bulk-edit', [RealisationModuleController::class, 'bulkEditForm'])
        ->name('realisationModules.bulkEdit');
        Route::post('realisationModules/bulk-update', [RealisationModuleController::class, 'bulkUpdate'])
        ->name('realisationModules.bulkUpdate');

        Route::resource('realisationModules', RealisationModuleController::class)
            ->parameters(['realisationModules' => 'realisationModule']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('realisationModules/import', [RealisationModuleController::class, 'import'])->name('realisationModules.import');
            Route::get('realisationModules/export/{format}', [RealisationModuleController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('realisationModules.export');

        });

        Route::post('realisationModules/data-calcul', [RealisationModuleController::class, 'dataCalcul'])->name('realisationModules.dataCalcul');
        Route::post('realisationModules/update-attributes', [RealisationModuleController::class, 'updateAttributes'])->name('realisationModules.updateAttributes');

    

    });
});
