<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgQcm\Controllers\AffectationQcmProjetController;

// routes for affectationQcmProjet management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgQcm')->group(function () {

        // Edition inline
        Route::get('affectationQcmProjets/{id}/field/{field}/meta', [AffectationQcmProjetController::class, 'fieldMeta'])
            ->name('affectationQcmProjets.field.meta');
        Route::patch('affectationQcmProjets/{id}/inline', [AffectationQcmProjetController::class, 'patchInline'])
            ->name('affectationQcmProjets.patchInline');

        Route::get('affectationQcmProjets/getData', [AffectationQcmProjetController::class, 'getData'])->name('affectationQcmProjets.getData');
        // ✅ Route JSON
        Route::get('affectationQcmProjets/json/{id}', [AffectationQcmProjetController::class, 'getAffectationQcmProjet'])
            ->name('affectationQcmProjets.getById');
        // bulk - edit and delete
        Route::post('affectationQcmProjets/bulk-delete', [AffectationQcmProjetController::class, 'bulkDelete'])
        ->name('affectationQcmProjets.bulkDelete');
        Route::get('affectationQcmProjets/bulk-edit', [AffectationQcmProjetController::class, 'bulkEditForm'])
        ->name('affectationQcmProjets.bulkEdit');
        Route::post('affectationQcmProjets/bulk-update', [AffectationQcmProjetController::class, 'bulkUpdate'])
        ->name('affectationQcmProjets.bulkUpdate');

        Route::resource('affectationQcmProjets', AffectationQcmProjetController::class)
            ->parameters(['affectationQcmProjets' => 'affectationQcmProjet']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('affectationQcmProjets/import', [AffectationQcmProjetController::class, 'import'])->name('affectationQcmProjets.import');
            Route::get('affectationQcmProjets/export/{format}', [AffectationQcmProjetController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('affectationQcmProjets.export');

        });

        Route::post('affectationQcmProjets/data-calcul', [AffectationQcmProjetController::class, 'dataCalcul'])->name('affectationQcmProjets.dataCalcul');
        Route::post('affectationQcmProjets/update-attributes', [AffectationQcmProjetController::class, 'updateAttributes'])->name('affectationQcmProjets.updateAttributes');

    

    });
});
