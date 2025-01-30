<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgFormation\Controllers\AnneeFormationController;

// routes for anneeFormation management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgFormation')->group(function () {

        Route::get('anneeFormations/getAnneeFormations', [AnneeFormationController::class, 'getAnneeFormations'])->name('anneeFormations.all');
        Route::resource('anneeFormations', AnneeFormationController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('anneeFormations/export', [AnneeFormationController::class, 'export'])->name('anneeFormations.export');
            Route::post('anneeFormations/import', [AnneeFormationController::class, 'import'])->name('anneeFormations.import');
        });
    });
});
