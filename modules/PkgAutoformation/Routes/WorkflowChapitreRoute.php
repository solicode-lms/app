<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgAutoformation\Controllers\WorkflowChapitreController;

// routes for workflowChapitre management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgAutoformation')->group(function () {

        Route::get('workflowChapitres/getData', [WorkflowChapitreController::class, 'getData'])->name('workflowChapitres.getData');
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

    });
});
