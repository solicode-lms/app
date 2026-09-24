<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgQcm\Controllers\RealisationQcmController;

// routes for realisationQcm management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgQcm')->group(function () {

        // Edition inline
        Route::get('realisationQcms/{id}/field/{field}/meta', [RealisationQcmController::class, 'fieldMeta'])
            ->name('realisationQcms.field.meta');
        Route::patch('realisationQcms/{id}/inline', [RealisationQcmController::class, 'patchInline'])
            ->name('realisationQcms.patchInline');

        Route::get('realisationQcms/getData', [RealisationQcmController::class, 'getData'])->name('realisationQcms.getData');
        // ✅ Route JSON
        Route::get('realisationQcms/json/{id}', [RealisationQcmController::class, 'getRealisationQcm'])
            ->name('realisationQcms.getById');
        // bulk - edit and delete
        Route::post('realisationQcms/bulk-delete', [RealisationQcmController::class, 'bulkDelete'])
        ->name('realisationQcms.bulkDelete');
        Route::get('realisationQcms/bulk-edit', [RealisationQcmController::class, 'bulkEditForm'])
        ->name('realisationQcms.bulkEdit');
        Route::post('realisationQcms/bulk-update', [RealisationQcmController::class, 'bulkUpdate'])
        ->name('realisationQcms.bulkUpdate');

        Route::resource('realisationQcms', RealisationQcmController::class)
            ->parameters(['realisationQcms' => 'realisationQcm']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('realisationQcms/import', [RealisationQcmController::class, 'import'])->name('realisationQcms.import');
            Route::get('realisationQcms/export/{format}', [RealisationQcmController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('realisationQcms.export');

        });

        Route::post('realisationQcms/data-calcul', [RealisationQcmController::class, 'dataCalcul'])->name('realisationQcms.dataCalcul');
        Route::post('realisationQcms/update-attributes', [RealisationQcmController::class, 'updateAttributes'])->name('realisationQcms.updateAttributes');
        Route::get('realisationQcms/initQcm/{id}', [RealisationQcmController::class, 'initQcm'])->name('realisationQcms.initQcm');
    
    

    });
});
