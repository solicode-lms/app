<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgFormation\Controllers\SpecialiteController;

// routes for specialite management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgFormation')->group(function () {

        Route::get('specialites/getSpecialites', [SpecialiteController::class, 'getSpecialites'])->name('specialites.all');
        Route::resource('specialites', SpecialiteController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('specialites/export', [SpecialiteController::class, 'export'])->name('specialites.export');
            Route::post('specialites/import', [SpecialiteController::class, 'import'])->name('specialites.import');
        });
    });
});
