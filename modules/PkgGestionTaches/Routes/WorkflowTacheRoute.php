<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGestionTaches\Controllers\WorkflowTacheController;

// routes for workflowTache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGestionTaches')->group(function () {

        Route::get('workflowTaches/getData', [WorkflowTacheController::class, 'getData'])->name('workflowTaches.getData');
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
