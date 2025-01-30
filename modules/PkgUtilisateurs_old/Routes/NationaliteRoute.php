<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgUtilisateurs\Controllers\NationaliteController;

// routes for nationalite management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgUtilisateurs')->group(function () {

        Route::get('nationalites/getNationalites', [NationaliteController::class, 'getNationalites'])->name('nationalites.all');
        Route::resource('nationalites', NationaliteController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('nationalites/export', [NationaliteController::class, 'export'])->name('nationalites.export');
            Route::post('nationalites/import', [NationaliteController::class, 'import'])->name('nationalites.import');
        });
    });
});
