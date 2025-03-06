<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGestionTaches\Controllers\WorkflowTacheController;

// routes for workflowTache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGestionTaches')->group(function () {

        Route::get('workflowTaches/getWorkflowTaches', [WorkflowTacheController::class, 'getWorkflowTaches'])->name('workflowTaches.all');
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

    });
});
