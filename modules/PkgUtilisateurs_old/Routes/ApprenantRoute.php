<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgUtilisateurs\Controllers\ApprenantController;

// routes for apprenant management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgUtilisateurs')->group(function () {

        Route::get('apprenants/getApprenants', [ApprenantController::class, 'getApprenants'])->name('apprenants.all');
        Route::resource('apprenants', ApprenantController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('apprenants/export', [ApprenantController::class, 'export'])->name('apprenants.export');
            Route::post('apprenants/import', [ApprenantController::class, 'import'])->name('apprenants.import');
        });
    });
});
