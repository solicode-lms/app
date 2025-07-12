<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgEvaluateurs\Controllers\EvaluationRealisationProjetController;

// routes for evaluationRealisationProjet management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgEvaluateurs')->group(function () {
        Route::get('evaluationRealisationProjets/getData', [EvaluationRealisationProjetController::class, 'getData'])->name('evaluationRealisationProjets.getData');
        // bulk - edit and delete
        Route::post('evaluationRealisationProjets/bulk-delete', [EvaluationRealisationProjetController::class, 'bulkDelete'])
        ->name('evaluationRealisationProjets.bulkDelete');
        Route::get('evaluationRealisationProjets/bulk-edit', [EvaluationRealisationProjetController::class, 'bulkEditForm'])
        ->name('evaluationRealisationProjets.bulkEdit');
        Route::post('evaluationRealisationProjets/bulk-update', [EvaluationRealisationProjetController::class, 'bulkUpdate'])
        ->name('evaluationRealisationProjets.bulkUpdate');

        Route::resource('evaluationRealisationProjets', EvaluationRealisationProjetController::class)
            ->parameters(['evaluationRealisationProjets' => 'evaluationRealisationProjet']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('evaluationRealisationProjets/import', [EvaluationRealisationProjetController::class, 'import'])->name('evaluationRealisationProjets.import');
            Route::get('evaluationRealisationProjets/export/{format}', [EvaluationRealisationProjetController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('evaluationRealisationProjets.export');

        });

        Route::post('evaluationRealisationProjets/data-calcul', [EvaluationRealisationProjetController::class, 'dataCalcul'])->name('evaluationRealisationProjets.dataCalcul');
        Route::post('evaluationRealisationProjets/update-attributes', [EvaluationRealisationProjetController::class, 'updateAttributes'])->name('evaluationRealisationProjets.updateAttributes');

    

    });
});
