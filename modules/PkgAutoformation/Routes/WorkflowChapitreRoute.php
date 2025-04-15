<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgAutoformation\Controllers\WorkflowChapitreController;

// routes for workflowChapitre management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgAutoformation')->group(function () {

        Route::get('workflowChapitres/getData', [WorkflowChapitreController::class, 'getData'])->name('workflowChapitres.getData');
        // bulk - edit and delete
        Route::post('workflowChapitres/bulk-delete', [WorkflowChapitreController::class, 'bulkDelete'])
        ->name('workflowChapitres.bulkDelete');
        Route::get('workflowChapitres/bulk-edit', [WorkflowChapitreController::class, 'bulkEditForm'])
        ->name('workflowChapitres.bulkEdit');
        Route::post('workflowChapitres/bulk-update', [WorkflowChapitreController::class, 'bulkUpdate'])
        ->name('workflowChapitres.bulkUpdate');

        Route::resource('workflowChapitres', WorkflowChapitreController::class)
            ->parameters(['workflowChapitres' => 'workflowChapitre']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('workflowChapitres/import', [WorkflowChapitreController::class, 'import'])->name('workflowChapitres.import');
            Route::get('workflowChapitres/export/{format}', [WorkflowChapitreController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('workflowChapitres.export');

        });

        Route::post('workflowChapitres/data-calcul', [WorkflowChapitreController::class, 'dataCalcul'])->name('workflowChapitres.dataCalcul');
        Route::post('workflowChapitres/update-attributes', [WorkflowChapitreController::class, 'updateAttributes'])->name('workflowChapitres.updateAttributes');

    

    });
});
