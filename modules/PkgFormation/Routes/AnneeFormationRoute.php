<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgFormation\Controllers\AnneeFormationController;

// routes for anneeFormation management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgFormation')->group(function () {

        // Edition inline
        Route::get('anneeFormations/{id}/field/{field}/meta', [AnneeFormationController::class, 'fieldMeta'])
            ->name('anneeFormations.field.meta');
        Route::patch('anneeFormations/{id}/inline', [AnneeFormationController::class, 'patchInline'])
            ->name('anneeFormations.patchInline');

        Route::get('anneeFormations/getData', [AnneeFormationController::class, 'getData'])->name('anneeFormations.getData');
        // ✅ Route JSON
        Route::get('anneeFormations/json/{id}', [AnneeFormationController::class, 'getAnneeFormation'])
            ->name('anneeFormations.getById');
        // bulk - edit and delete
        Route::post('anneeFormations/bulk-delete', [AnneeFormationController::class, 'bulkDelete'])
        ->name('anneeFormations.bulkDelete');
        Route::get('anneeFormations/bulk-edit', [AnneeFormationController::class, 'bulkEditForm'])
        ->name('anneeFormations.bulkEdit');
        Route::post('anneeFormations/bulk-update', [AnneeFormationController::class, 'bulkUpdate'])
        ->name('anneeFormations.bulkUpdate');

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
        Route::post('anneeFormations/update-attributes', [AnneeFormationController::class, 'updateAttributes'])->name('anneeFormations.updateAttributes');

    

    });
});
