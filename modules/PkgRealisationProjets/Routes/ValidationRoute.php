<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgRealisationProjets\Controllers\ValidationController;

// routes for validation management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgRealisationProjets')->group(function () {

        Route::get('validations/getValidations', [ValidationController::class, 'getValidations'])->name('validations.all');
        Route::resource('validations', ValidationController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('validations/import', [ValidationController::class, 'import'])->name('validations.import');
            Route::get('validations/export/{format}', [ValidationController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('validations.export');

        });

        Route::post('validations/data-calcul', [ValidationController::class, 'dataCalcul'])->name('validations.dataCalcul');

    });
});
