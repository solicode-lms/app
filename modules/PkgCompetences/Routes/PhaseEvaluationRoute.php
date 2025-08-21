<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCompetences\Controllers\PhaseEvaluationController;

// routes for phaseEvaluation management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCompetences')->group(function () {

        // Edition inline
        Route::get('phaseEvaluations/{id}/field/{field}/meta', [PhaseEvaluationController::class, 'fieldMeta'])
            ->name('phaseEvaluations.field.meta');
        Route::patch('phaseEvaluations/{id}/inline', [PhaseEvaluationController::class, 'patchInline'])
            ->name('phaseEvaluations.patchInline');

        Route::get('phaseEvaluations/getData', [PhaseEvaluationController::class, 'getData'])->name('phaseEvaluations.getData');
        // ✅ Route JSON
        Route::get('phaseEvaluations/json/{id}', [PhaseEvaluationController::class, 'getPhaseEvaluation'])
            ->name('phaseEvaluations.getById');
        // bulk - edit and delete
        Route::post('phaseEvaluations/bulk-delete', [PhaseEvaluationController::class, 'bulkDelete'])
        ->name('phaseEvaluations.bulkDelete');
        Route::get('phaseEvaluations/bulk-edit', [PhaseEvaluationController::class, 'bulkEditForm'])
        ->name('phaseEvaluations.bulkEdit');
        Route::post('phaseEvaluations/bulk-update', [PhaseEvaluationController::class, 'bulkUpdate'])
        ->name('phaseEvaluations.bulkUpdate');

        Route::resource('phaseEvaluations', PhaseEvaluationController::class)
            ->parameters(['phaseEvaluations' => 'phaseEvaluation']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('phaseEvaluations/import', [PhaseEvaluationController::class, 'import'])->name('phaseEvaluations.import');
            Route::get('phaseEvaluations/export/{format}', [PhaseEvaluationController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('phaseEvaluations.export');

        });

        Route::post('phaseEvaluations/data-calcul', [PhaseEvaluationController::class, 'dataCalcul'])->name('phaseEvaluations.dataCalcul');
        Route::post('phaseEvaluations/update-attributes', [PhaseEvaluationController::class, 'updateAttributes'])->name('phaseEvaluations.updateAttributes');

    

    });
});
