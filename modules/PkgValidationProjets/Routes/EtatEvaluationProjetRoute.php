<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgValidationProjets\Controllers\EtatEvaluationProjetController;

// routes for etatEvaluationProjet management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgValidationProjets')->group(function () {
        Route::get('etatEvaluationProjets/getData', [EtatEvaluationProjetController::class, 'getData'])->name('etatEvaluationProjets.getData');
        // bulk - edit and delete
        Route::post('etatEvaluationProjets/bulk-delete', [EtatEvaluationProjetController::class, 'bulkDelete'])
        ->name('etatEvaluationProjets.bulkDelete');
        Route::get('etatEvaluationProjets/bulk-edit', [EtatEvaluationProjetController::class, 'bulkEditForm'])
        ->name('etatEvaluationProjets.bulkEdit');
        Route::post('etatEvaluationProjets/bulk-update', [EtatEvaluationProjetController::class, 'bulkUpdate'])
        ->name('etatEvaluationProjets.bulkUpdate');

        Route::resource('etatEvaluationProjets', EtatEvaluationProjetController::class)
            ->parameters(['etatEvaluationProjets' => 'etatEvaluationProjet']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('etatEvaluationProjets/import', [EtatEvaluationProjetController::class, 'import'])->name('etatEvaluationProjets.import');
            Route::get('etatEvaluationProjets/export/{format}', [EtatEvaluationProjetController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('etatEvaluationProjets.export');

        });

        Route::post('etatEvaluationProjets/data-calcul', [EtatEvaluationProjetController::class, 'dataCalcul'])->name('etatEvaluationProjets.dataCalcul');
        Route::post('etatEvaluationProjets/update-attributes', [EtatEvaluationProjetController::class, 'updateAttributes'])->name('etatEvaluationProjets.updateAttributes');

    

    });
});
