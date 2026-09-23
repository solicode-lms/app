<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgQcm\Controllers\PropositionReponseController;

// routes for propositionReponse management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgQcm')->group(function () {

        // Edition inline
        Route::get('propositionReponses/{id}/field/{field}/meta', [PropositionReponseController::class, 'fieldMeta'])
            ->name('propositionReponses.field.meta');
        Route::patch('propositionReponses/{id}/inline', [PropositionReponseController::class, 'patchInline'])
            ->name('propositionReponses.patchInline');

        Route::get('propositionReponses/getData', [PropositionReponseController::class, 'getData'])->name('propositionReponses.getData');
        // ✅ Route JSON
        Route::get('propositionReponses/json/{id}', [PropositionReponseController::class, 'getPropositionReponse'])
            ->name('propositionReponses.getById');
        // bulk - edit and delete
        Route::post('propositionReponses/bulk-delete', [PropositionReponseController::class, 'bulkDelete'])
        ->name('propositionReponses.bulkDelete');
        Route::get('propositionReponses/bulk-edit', [PropositionReponseController::class, 'bulkEditForm'])
        ->name('propositionReponses.bulkEdit');
        Route::post('propositionReponses/bulk-update', [PropositionReponseController::class, 'bulkUpdate'])
        ->name('propositionReponses.bulkUpdate');

        Route::resource('propositionReponses', PropositionReponseController::class)
            ->parameters(['propositionReponses' => 'propositionReponse']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('propositionReponses/import', [PropositionReponseController::class, 'import'])->name('propositionReponses.import');
            Route::get('propositionReponses/export/{format}', [PropositionReponseController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('propositionReponses.export');

        });

        Route::post('propositionReponses/data-calcul', [PropositionReponseController::class, 'dataCalcul'])->name('propositionReponses.dataCalcul');
        Route::post('propositionReponses/update-attributes', [PropositionReponseController::class, 'updateAttributes'])->name('propositionReponses.updateAttributes');

    

    });
});
