<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgApprenants\Controllers\VilleController;

// routes for ville management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgApprenants')->group(function () {
        Route::get('villes/getData', [VilleController::class, 'getData'])->name('villes.getData');
        // bulk - edit and delete
        Route::post('villes/bulk-delete', [VilleController::class, 'bulkDelete'])
        ->name('villes.bulkDelete');
        Route::get('villes/bulk-edit', [VilleController::class, 'bulkEditForm'])
        ->name('villes.bulkEdit');
        Route::post('villes/bulk-update', [VilleController::class, 'bulkUpdate'])
        ->name('villes.bulkUpdate');

        Route::resource('villes', VilleController::class)
            ->parameters(['villes' => 'ville']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('villes/import', [VilleController::class, 'import'])->name('villes.import');
            Route::get('villes/export/{format}', [VilleController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('villes.export');

        });

        Route::post('villes/data-calcul', [VilleController::class, 'dataCalcul'])->name('villes.dataCalcul');
        Route::post('villes/update-attributes', [VilleController::class, 'updateAttributes'])->name('villes.updateAttributes');

    

    });
});
