<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCompetences\Controllers\CritereEvaluationController;

// routes for critereEvaluation management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCompetences')->group(function () {
        Route::get('critereEvaluations/getData', [CritereEvaluationController::class, 'getData'])->name('critereEvaluations.getData');
        // ✅ Route JSON
        Route::get('critereEvaluations/json/{id}', [CritereEvaluationController::class, 'getCritereEvaluation'])
            ->name('critereEvaluations.getById');
        // bulk - edit and delete
        Route::post('critereEvaluations/bulk-delete', [CritereEvaluationController::class, 'bulkDelete'])
        ->name('critereEvaluations.bulkDelete');
        Route::get('critereEvaluations/bulk-edit', [CritereEvaluationController::class, 'bulkEditForm'])
        ->name('critereEvaluations.bulkEdit');
        Route::post('critereEvaluations/bulk-update', [CritereEvaluationController::class, 'bulkUpdate'])
        ->name('critereEvaluations.bulkUpdate');

        Route::resource('critereEvaluations', CritereEvaluationController::class)
            ->parameters(['critereEvaluations' => 'critereEvaluation']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('critereEvaluations/import', [CritereEvaluationController::class, 'import'])->name('critereEvaluations.import');
            Route::get('critereEvaluations/export/{format}', [CritereEvaluationController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('critereEvaluations.export');

        });

        Route::post('critereEvaluations/data-calcul', [CritereEvaluationController::class, 'dataCalcul'])->name('critereEvaluations.dataCalcul');
        Route::post('critereEvaluations/update-attributes', [CritereEvaluationController::class, 'updateAttributes'])->name('critereEvaluations.updateAttributes');

    

    });
});
