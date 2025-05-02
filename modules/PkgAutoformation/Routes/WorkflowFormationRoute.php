<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgAutoformation\Controllers\WorkflowFormationController;

// routes for workflowFormation management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgAutoformation')->group(function () {
        Route::get('workflowFormations/getData', [WorkflowFormationController::class, 'getData'])->name('workflowFormations.getData');
        // bulk - edit and delete
        Route::post('workflowFormations/bulk-delete', [WorkflowFormationController::class, 'bulkDelete'])
        ->name('workflowFormations.bulkDelete');
        Route::get('workflowFormations/bulk-edit', [WorkflowFormationController::class, 'bulkEditForm'])
        ->name('workflowFormations.bulkEdit');
        Route::post('workflowFormations/bulk-update', [WorkflowFormationController::class, 'bulkUpdate'])
        ->name('workflowFormations.bulkUpdate');

        Route::resource('workflowFormations', WorkflowFormationController::class)
            ->parameters(['workflowFormations' => 'workflowFormation']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('workflowFormations/import', [WorkflowFormationController::class, 'import'])->name('workflowFormations.import');
            Route::get('workflowFormations/export/{format}', [WorkflowFormationController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('workflowFormations.export');

        });

        Route::post('workflowFormations/data-calcul', [WorkflowFormationController::class, 'dataCalcul'])->name('workflowFormations.dataCalcul');
        Route::post('workflowFormations/update-attributes', [WorkflowFormationController::class, 'updateAttributes'])->name('workflowFormations.updateAttributes');

    

    });
});
