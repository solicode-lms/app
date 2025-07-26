<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgSessions\Controllers\SessionFormationController;

// routes for sessionFormation management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgSessions')->group(function () {
        Route::get('sessionFormations/getData', [SessionFormationController::class, 'getData'])->name('sessionFormations.getData');
        // bulk - edit and delete
        Route::post('sessionFormations/bulk-delete', [SessionFormationController::class, 'bulkDelete'])
        ->name('sessionFormations.bulkDelete');
        Route::get('sessionFormations/bulk-edit', [SessionFormationController::class, 'bulkEditForm'])
        ->name('sessionFormations.bulkEdit');
        Route::post('sessionFormations/bulk-update', [SessionFormationController::class, 'bulkUpdate'])
        ->name('sessionFormations.bulkUpdate');

        Route::resource('sessionFormations', SessionFormationController::class)
            ->parameters(['sessionFormations' => 'sessionFormation']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('sessionFormations/import', [SessionFormationController::class, 'import'])->name('sessionFormations.import');
            Route::get('sessionFormations/export/{format}', [SessionFormationController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('sessionFormations.export');

        });

        Route::post('sessionFormations/data-calcul', [SessionFormationController::class, 'dataCalcul'])->name('sessionFormations.dataCalcul');
        Route::post('sessionFormations/update-attributes', [SessionFormationController::class, 'updateAttributes'])->name('sessionFormations.updateAttributes');
        Route::get('sessionFormations/add_projet/{id}', [SessionFormationController::class, 'add_projet'])->name('sessionFormations.add_projet');
    
    

    });
});
