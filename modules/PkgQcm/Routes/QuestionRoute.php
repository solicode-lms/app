<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgQcm\Controllers\QuestionController;

// routes for question management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgQcm')->group(function () {

        // Edition inline
        Route::get('questions/{id}/field/{field}/meta', [QuestionController::class, 'fieldMeta'])
            ->name('questions.field.meta');
        Route::patch('questions/{id}/inline', [QuestionController::class, 'patchInline'])
            ->name('questions.patchInline');

        Route::get('questions/getData', [QuestionController::class, 'getData'])->name('questions.getData');
        // ✅ Route JSON
        Route::get('questions/json/{id}', [QuestionController::class, 'getQuestion'])
            ->name('questions.getById');
        // bulk - edit and delete
        Route::post('questions/bulk-delete', [QuestionController::class, 'bulkDelete'])
        ->name('questions.bulkDelete');
        Route::get('questions/bulk-edit', [QuestionController::class, 'bulkEditForm'])
        ->name('questions.bulkEdit');
        Route::post('questions/bulk-update', [QuestionController::class, 'bulkUpdate'])
        ->name('questions.bulkUpdate');

        Route::resource('questions', QuestionController::class)
            ->parameters(['questions' => 'question']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('questions/import', [QuestionController::class, 'import'])->name('questions.import');
            Route::get('questions/export/{format}', [QuestionController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('questions.export');

        });

        Route::post('questions/data-calcul', [QuestionController::class, 'dataCalcul'])->name('questions.dataCalcul');
        Route::post('questions/update-attributes', [QuestionController::class, 'updateAttributes'])->name('questions.updateAttributes');
        Route::get('questions/importIaForm/{id}', [QuestionController::class, 'importIaForm'])->name('questions.importIaForm');
    
    

    });
});
