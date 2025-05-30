<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgAutoformation\Controllers\EtatFormationController;

// routes for etatFormation management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgAutoformation')->group(function () {
        Route::get('etatFormations/getData', [EtatFormationController::class, 'getData'])->name('etatFormations.getData');
        // bulk - edit and delete
        Route::post('etatFormations/bulk-delete', [EtatFormationController::class, 'bulkDelete'])
        ->name('etatFormations.bulkDelete');
        Route::get('etatFormations/bulk-edit', [EtatFormationController::class, 'bulkEditForm'])
        ->name('etatFormations.bulkEdit');
        Route::post('etatFormations/bulk-update', [EtatFormationController::class, 'bulkUpdate'])
        ->name('etatFormations.bulkUpdate');

        Route::resource('etatFormations', EtatFormationController::class)
            ->parameters(['etatFormations' => 'etatFormation']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('etatFormations/import', [EtatFormationController::class, 'import'])->name('etatFormations.import');
            Route::get('etatFormations/export/{format}', [EtatFormationController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('etatFormations.export');

        });

        Route::post('etatFormations/data-calcul', [EtatFormationController::class, 'dataCalcul'])->name('etatFormations.dataCalcul');
        Route::post('etatFormations/update-attributes', [EtatFormationController::class, 'updateAttributes'])->name('etatFormations.updateAttributes');

    

    });
});
