<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgAutoformation\Controllers\RealisationFormationController;

// routes for realisationFormation management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgAutoformation')->group(function () {
        Route::get('realisationFormations/getData', [RealisationFormationController::class, 'getData'])->name('realisationFormations.getData');
        // bulk - edit and delete
        Route::post('realisationFormations/bulk-delete', [RealisationFormationController::class, 'bulkDelete'])
        ->name('realisationFormations.bulkDelete');
        Route::get('realisationFormations/bulk-edit', [RealisationFormationController::class, 'bulkEditForm'])
        ->name('realisationFormations.bulkEdit');
        Route::post('realisationFormations/bulk-update', [RealisationFormationController::class, 'bulkUpdate'])
        ->name('realisationFormations.bulkUpdate');

        Route::resource('realisationFormations', RealisationFormationController::class)
            ->parameters(['realisationFormations' => 'realisationFormation']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('realisationFormations/import', [RealisationFormationController::class, 'import'])->name('realisationFormations.import');
            Route::get('realisationFormations/export/{format}', [RealisationFormationController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('realisationFormations.export');

        });

        Route::post('realisationFormations/data-calcul', [RealisationFormationController::class, 'dataCalcul'])->name('realisationFormations.dataCalcul');
        Route::post('realisationFormations/update-attributes', [RealisationFormationController::class, 'updateAttributes'])->name('realisationFormations.updateAttributes');

    

    });
});
