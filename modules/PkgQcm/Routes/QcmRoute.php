<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgQcm\Controllers\QcmController;

// routes for qcm management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgQcm')->group(function () {

        // Edition inline
        Route::get('qcms/{id}/field/{field}/meta', [QcmController::class, 'fieldMeta'])
            ->name('qcms.field.meta');
        Route::patch('qcms/{id}/inline', [QcmController::class, 'patchInline'])
            ->name('qcms.patchInline');

        Route::get('qcms/getData', [QcmController::class, 'getData'])->name('qcms.getData');
        // ✅ Route JSON
        Route::get('qcms/json/{id}', [QcmController::class, 'getQcm'])
            ->name('qcms.getById');
        // bulk - edit and delete
        Route::post('qcms/bulk-delete', [QcmController::class, 'bulkDelete'])
        ->name('qcms.bulkDelete');
        Route::get('qcms/bulk-edit', [QcmController::class, 'bulkEditForm'])
        ->name('qcms.bulkEdit');
        Route::post('qcms/bulk-update', [QcmController::class, 'bulkUpdate'])
        ->name('qcms.bulkUpdate');

        Route::resource('qcms', QcmController::class)
            ->parameters(['qcms' => 'qcm']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('qcms/import', [QcmController::class, 'import'])->name('qcms.import');
            Route::get('qcms/export/{format}', [QcmController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('qcms.export');

        });

        Route::post('qcms/data-calcul', [QcmController::class, 'dataCalcul'])->name('qcms.dataCalcul');
        Route::post('qcms/update-attributes', [QcmController::class, 'updateAttributes'])->name('qcms.updateAttributes');

    

    });
});
