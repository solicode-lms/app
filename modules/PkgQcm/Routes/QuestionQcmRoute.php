<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgQcm\Controllers\QuestionQcmController;

// routes for questionQcm management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgQcm')->group(function () {

        // Edition inline
        Route::get('questionQcms/{id}/field/{field}/meta', [QuestionQcmController::class, 'fieldMeta'])
            ->name('questionQcms.field.meta');
        Route::patch('questionQcms/{id}/inline', [QuestionQcmController::class, 'patchInline'])
            ->name('questionQcms.patchInline');

        Route::get('questionQcms/getData', [QuestionQcmController::class, 'getData'])->name('questionQcms.getData');
        // ✅ Route JSON
        Route::get('questionQcms/json/{id}', [QuestionQcmController::class, 'getQuestionQcm'])
            ->name('questionQcms.getById');
        // bulk - edit and delete
        Route::post('questionQcms/bulk-delete', [QuestionQcmController::class, 'bulkDelete'])
        ->name('questionQcms.bulkDelete');
        Route::get('questionQcms/bulk-edit', [QuestionQcmController::class, 'bulkEditForm'])
        ->name('questionQcms.bulkEdit');
        Route::post('questionQcms/bulk-update', [QuestionQcmController::class, 'bulkUpdate'])
        ->name('questionQcms.bulkUpdate');

        Route::resource('questionQcms', QuestionQcmController::class)
            ->parameters(['questionQcms' => 'questionQcm']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('questionQcms/import', [QuestionQcmController::class, 'import'])->name('questionQcms.import');
            Route::get('questionQcms/export/{format}', [QuestionQcmController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('questionQcms.export');

        });

        Route::post('questionQcms/data-calcul', [QuestionQcmController::class, 'dataCalcul'])->name('questionQcms.dataCalcul');
        Route::post('questionQcms/update-attributes', [QuestionQcmController::class, 'updateAttributes'])->name('questionQcms.updateAttributes');

    

    });
});
