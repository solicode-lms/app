<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgRealisationTache\Controllers\WorkflowTacheController;

// routes for workflowTache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgRealisationTache')->group(function () {

        // Edition inline
        Route::get('workflowTaches/{id}/field/{field}/meta', [WorkflowTacheController::class, 'fieldMeta'])
            ->name('workflowTaches.field.meta');
        Route::patch('workflowTaches/{id}/inline', [WorkflowTacheController::class, 'patchInline'])
            ->name('workflowTaches.patchInline');

         Route::get('workflowTaches/resyncEtatsFormateurs', [WorkflowTacheController::class, 'resyncEtatsFormateurs'])->name('workflowTaches.resyncEtatsFormateurs');
        Route::get('workflowTaches/getData', [WorkflowTacheController::class, 'getData'])->name('workflowTaches.getData');
        // ✅ Route JSON
        Route::get('workflowTaches/json/{id}', [WorkflowTacheController::class, 'getWorkflowTache'])
            ->name('workflowTaches.getById');
        // bulk - edit and delete
        Route::post('workflowTaches/bulk-delete', [WorkflowTacheController::class, 'bulkDelete'])
        ->name('workflowTaches.bulkDelete');
        Route::get('workflowTaches/bulk-edit', [WorkflowTacheController::class, 'bulkEditForm'])
        ->name('workflowTaches.bulkEdit');
        Route::post('workflowTaches/bulk-update', [WorkflowTacheController::class, 'bulkUpdate'])
        ->name('workflowTaches.bulkUpdate');

        Route::resource('workflowTaches', WorkflowTacheController::class)
            ->parameters(['workflowTaches' => 'workflowTache']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('workflowTaches/import', [WorkflowTacheController::class, 'import'])->name('workflowTaches.import');
            Route::get('workflowTaches/export/{format}', [WorkflowTacheController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('workflowTaches.export');

        });

        Route::post('workflowTaches/data-calcul', [WorkflowTacheController::class, 'dataCalcul'])->name('workflowTaches.dataCalcul');
        Route::post('workflowTaches/update-attributes', [WorkflowTacheController::class, 'updateAttributes'])->name('workflowTaches.updateAttributes');

    

    });
});
