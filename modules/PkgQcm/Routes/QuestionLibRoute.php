<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgQcm\Controllers\QuestionLibController;

// routes for questionLib management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgQcm')->group(function () {

        // Edition inline
        Route::get('questionLibs/{id}/field/{field}/meta', [QuestionLibController::class, 'fieldMeta'])
            ->name('questionLibs.field.meta');
        Route::patch('questionLibs/{id}/inline', [QuestionLibController::class, 'patchInline'])
            ->name('questionLibs.patchInline');

        Route::get('questionLibs/getData', [QuestionLibController::class, 'getData'])->name('questionLibs.getData');
        // ✅ Route JSON
        Route::get('questionLibs/json/{id}', [QuestionLibController::class, 'getQuestionLib'])
            ->name('questionLibs.getById');
        // bulk - edit and delete
        Route::post('questionLibs/bulk-delete', [QuestionLibController::class, 'bulkDelete'])
        ->name('questionLibs.bulkDelete');
        Route::get('questionLibs/bulk-edit', [QuestionLibController::class, 'bulkEditForm'])
        ->name('questionLibs.bulkEdit');
        Route::post('questionLibs/bulk-update', [QuestionLibController::class, 'bulkUpdate'])
        ->name('questionLibs.bulkUpdate');

        Route::resource('questionLibs', QuestionLibController::class)
            ->parameters(['questionLibs' => 'questionLib']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('questionLibs/import', [QuestionLibController::class, 'import'])->name('questionLibs.import');
            Route::get('questionLibs/export/{format}', [QuestionLibController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('questionLibs.export');

        });

        Route::post('questionLibs/data-calcul', [QuestionLibController::class, 'dataCalcul'])->name('questionLibs.dataCalcul');
        Route::post('questionLibs/update-attributes', [QuestionLibController::class, 'updateAttributes'])->name('questionLibs.updateAttributes');

    

    });
});
