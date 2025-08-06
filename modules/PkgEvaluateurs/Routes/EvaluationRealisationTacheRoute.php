<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgEvaluateurs\Controllers\EvaluationRealisationTacheController;

// routes for evaluationRealisationTache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgEvaluateurs')->group(function () {
        Route::get('evaluationRealisationTaches/getData', [EvaluationRealisationTacheController::class, 'getData'])->name('evaluationRealisationTaches.getData');
        // ✅ Route JSON
        Route::get('evaluationRealisationTaches/json/{id}', [EvaluationRealisationTacheController::class, 'getEvaluationRealisationTache'])
            ->name('evaluationRealisationTaches.getById');
        // bulk - edit and delete
        Route::post('evaluationRealisationTaches/bulk-delete', [EvaluationRealisationTacheController::class, 'bulkDelete'])
        ->name('evaluationRealisationTaches.bulkDelete');
        Route::get('evaluationRealisationTaches/bulk-edit', [EvaluationRealisationTacheController::class, 'bulkEditForm'])
        ->name('evaluationRealisationTaches.bulkEdit');
        Route::post('evaluationRealisationTaches/bulk-update', [EvaluationRealisationTacheController::class, 'bulkUpdate'])
        ->name('evaluationRealisationTaches.bulkUpdate');

        Route::resource('evaluationRealisationTaches', EvaluationRealisationTacheController::class)
            ->parameters(['evaluationRealisationTaches' => 'evaluationRealisationTache']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('evaluationRealisationTaches/import', [EvaluationRealisationTacheController::class, 'import'])->name('evaluationRealisationTaches.import');
            Route::get('evaluationRealisationTaches/export/{format}', [EvaluationRealisationTacheController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('evaluationRealisationTaches.export');

        });

        Route::post('evaluationRealisationTaches/data-calcul', [EvaluationRealisationTacheController::class, 'dataCalcul'])->name('evaluationRealisationTaches.dataCalcul');
        Route::post('evaluationRealisationTaches/update-attributes', [EvaluationRealisationTacheController::class, 'updateAttributes'])->name('evaluationRealisationTaches.updateAttributes');

    

    });
});
