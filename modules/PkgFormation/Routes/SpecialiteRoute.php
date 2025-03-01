<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgFormation\Controllers\SpecialiteController;

// routes for specialite management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgFormation')->group(function () {

        Route::get('specialites/getSpecialites', [SpecialiteController::class, 'getSpecialites'])->name('specialites.all');
        Route::resource('specialites', SpecialiteController::class)
            ->parameters(['specialites' => 'specialite']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('specialites/import', [SpecialiteController::class, 'import'])->name('specialites.import');
            Route::get('specialites/export/{format}', [SpecialiteController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('specialites.export');

        });

        Route::post('specialites/data-calcul', [SpecialiteController::class, 'dataCalcul'])->name('specialites.dataCalcul');

    });
});
