<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgApprenants\Controllers\ApprenantKonosyController;

// routes for apprenantKonosy management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgApprenants')->group(function () {

        Route::get('apprenantKonosies/getApprenantKonosies', [ApprenantKonosyController::class, 'getApprenantKonosies'])->name('apprenantKonosies.all');
        Route::resource('apprenantKonosies', ApprenantKonosyController::class)
            ->parameters(['apprenantKonosies' => 'apprenantKonosy']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('apprenantKonosies/import', [ApprenantKonosyController::class, 'import'])->name('apprenantKonosies.import');
            Route::get('apprenantKonosies/export/{format}', [ApprenantKonosyController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('apprenantKonosies.export');

        });

        Route::post('apprenantKonosies/data-calcul', [ApprenantKonosyController::class, 'dataCalcul'])->name('apprenantKonosies.dataCalcul');

    });
});
