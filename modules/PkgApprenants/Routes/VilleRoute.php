<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgApprenants\Controllers\VilleController;

// routes for ville management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgApprenants')->group(function () {

        Route::get('villes/getVilles', [VilleController::class, 'getVilles'])->name('villes.all');
        Route::resource('villes', VilleController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('villes/import', [VilleController::class, 'import'])->name('villes.import');
            Route::get('villes/export/{format}', [VilleController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('villes.export');

        });

        Route::post('villes/data-calcul', [VilleController::class, 'dataCalcul'])->name('villes.dataCalcul');

    });
});
