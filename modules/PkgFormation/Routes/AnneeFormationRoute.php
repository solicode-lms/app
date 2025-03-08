<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgFormation\Controllers\AnneeFormationController;

// routes for anneeFormation management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgFormation')->group(function () {

        Route::get('anneeFormations/getData', [AnneeFormationController::class, 'getData'])->name('anneeFormations.getData');
        Route::resource('anneeFormations', AnneeFormationController::class)
            ->parameters(['anneeFormations' => 'anneeFormation']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('anneeFormations/import', [AnneeFormationController::class, 'import'])->name('anneeFormations.import');
            Route::get('anneeFormations/export/{format}', [AnneeFormationController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('anneeFormations.export');

        });

        Route::post('anneeFormations/data-calcul', [AnneeFormationController::class, 'dataCalcul'])->name('anneeFormations.dataCalcul');

    });
});
