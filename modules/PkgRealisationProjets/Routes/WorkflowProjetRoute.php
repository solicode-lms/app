<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgRealisationProjets\Controllers\WorkflowProjetController;

// routes for workflowProjet management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgRealisationProjets')->group(function () {
        Route::get('workflowProjets/getData', [WorkflowProjetController::class, 'getData'])->name('workflowProjets.getData');
        // bulk - edit and delete
        Route::post('workflowProjets/bulk-delete', [WorkflowProjetController::class, 'bulkDelete'])
        ->name('workflowProjets.bulkDelete');
        Route::get('workflowProjets/bulk-edit', [WorkflowProjetController::class, 'bulkEditForm'])
        ->name('workflowProjets.bulkEdit');
        Route::post('workflowProjets/bulk-update', [WorkflowProjetController::class, 'bulkUpdate'])
        ->name('workflowProjets.bulkUpdate');

        Route::resource('workflowProjets', WorkflowProjetController::class)
            ->parameters(['workflowProjets' => 'workflowProjet']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('workflowProjets/import', [WorkflowProjetController::class, 'import'])->name('workflowProjets.import');
            Route::get('workflowProjets/export/{format}', [WorkflowProjetController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('workflowProjets.export');

        });

        Route::post('workflowProjets/data-calcul', [WorkflowProjetController::class, 'dataCalcul'])->name('workflowProjets.dataCalcul');
        Route::post('workflowProjets/update-attributes', [WorkflowProjetController::class, 'updateAttributes'])->name('workflowProjets.updateAttributes');

    

    });
});
