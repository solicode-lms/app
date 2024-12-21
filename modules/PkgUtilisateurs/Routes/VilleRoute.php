<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgUtilisateurs\Controllers\VilleController;

// routes for ville management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgUtilisateurs')->group(function () {

        Route::get('villes/getVilles', [VilleController::class, 'getVilles'])->name('villes.all');
        Route::resource('villes', VilleController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('villes/export', [VilleController::class, 'export'])->name('villes.export');
            Route::post('villes/import', [VilleController::class, 'import'])->name('villes.import');
        });
    });
});
