<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgQcm\Controllers\EtatRealisationQcmController;

// routes for etatRealisationQcm management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgQcm')->group(function () {

        // Edition inline
        Route::get('etatRealisationQcms/{id}/field/{field}/meta', [EtatRealisationQcmController::class, 'fieldMeta'])
            ->name('etatRealisationQcms.field.meta');
        Route::patch('etatRealisationQcms/{id}/inline', [EtatRealisationQcmController::class, 'patchInline'])
            ->name('etatRealisationQcms.patchInline');

        Route::get('etatRealisationQcms/getData', [EtatRealisationQcmController::class, 'getData'])->name('etatRealisationQcms.getData');
        // ✅ Route JSON
        Route::get('etatRealisationQcms/json/{id}', [EtatRealisationQcmController::class, 'getEtatRealisationQcm'])
            ->name('etatRealisationQcms.getById');
        // bulk - edit and delete
        Route::post('etatRealisationQcms/bulk-delete', [EtatRealisationQcmController::class, 'bulkDelete'])
        ->name('etatRealisationQcms.bulkDelete');
        Route::get('etatRealisationQcms/bulk-edit', [EtatRealisationQcmController::class, 'bulkEditForm'])
        ->name('etatRealisationQcms.bulkEdit');
        Route::post('etatRealisationQcms/bulk-update', [EtatRealisationQcmController::class, 'bulkUpdate'])
        ->name('etatRealisationQcms.bulkUpdate');

        Route::resource('etatRealisationQcms', EtatRealisationQcmController::class)
            ->parameters(['etatRealisationQcms' => 'etatRealisationQcm']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('etatRealisationQcms/import', [EtatRealisationQcmController::class, 'import'])->name('etatRealisationQcms.import');
            Route::get('etatRealisationQcms/export/{format}', [EtatRealisationQcmController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('etatRealisationQcms.export');

        });

        Route::post('etatRealisationQcms/data-calcul', [EtatRealisationQcmController::class, 'dataCalcul'])->name('etatRealisationQcms.dataCalcul');
        Route::post('etatRealisationQcms/update-attributes', [EtatRealisationQcmController::class, 'updateAttributes'])->name('etatRealisationQcms.updateAttributes');

    

    });
});
