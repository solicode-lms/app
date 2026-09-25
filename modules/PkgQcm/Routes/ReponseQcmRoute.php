<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgQcm\Controllers\ReponseQcmController;

// routes for reponseQcm management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgQcm')->group(function () {

        // Edition inline
        Route::get('reponseQcms/{id}/field/{field}/meta', [ReponseQcmController::class, 'fieldMeta'])
            ->name('reponseQcms.field.meta');
        Route::patch('reponseQcms/{id}/inline', [ReponseQcmController::class, 'patchInline'])
            ->name('reponseQcms.patchInline');

        Route::get('reponseQcms/getData', [ReponseQcmController::class, 'getData'])->name('reponseQcms.getData');
        // ✅ Route JSON
        Route::get('reponseQcms/json/{id}', [ReponseQcmController::class, 'getReponseQcm'])
            ->name('reponseQcms.getById');
        // bulk - edit and delete
        Route::post('reponseQcms/bulk-delete', [ReponseQcmController::class, 'bulkDelete'])
        ->name('reponseQcms.bulkDelete');
        Route::get('reponseQcms/bulk-edit', [ReponseQcmController::class, 'bulkEditForm'])
        ->name('reponseQcms.bulkEdit');
        Route::post('reponseQcms/bulk-update', [ReponseQcmController::class, 'bulkUpdate'])
        ->name('reponseQcms.bulkUpdate');

        Route::resource('reponseQcms', ReponseQcmController::class)
            ->parameters(['reponseQcms' => 'reponseQcm']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('reponseQcms/import', [ReponseQcmController::class, 'import'])->name('reponseQcms.import');
            Route::get('reponseQcms/export/{format}', [ReponseQcmController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('reponseQcms.export');

        });

        Route::post('reponseQcms/data-calcul', [ReponseQcmController::class, 'dataCalcul'])->name('reponseQcms.dataCalcul');
        Route::post('reponseQcms/update-attributes', [ReponseQcmController::class, 'updateAttributes'])->name('reponseQcms.updateAttributes');

    

    });
});
