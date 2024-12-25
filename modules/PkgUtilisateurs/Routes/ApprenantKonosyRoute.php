<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgUtilisateurs\Controllers\ApprenantKonosyController;

// routes for apprenantKonosy management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgUtilisateurs')->group(function () {

        Route::get('apprenantKonosies/getApprenantKonosies', [ApprenantKonosyController::class, 'getApprenantKonosies'])->name('apprenantKonosies.all');
        Route::resource('apprenantKonosies', ApprenantKonosyController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('apprenantKonosies/export', [ApprenantKonosyController::class, 'export'])->name('apprenantKonosies.export');
            Route::post('apprenantKonosies/import', [ApprenantKonosyController::class, 'import'])->name('apprenantKonosies.import');
        });
    });
});
