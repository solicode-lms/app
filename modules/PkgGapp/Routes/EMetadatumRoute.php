<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGapp\Controllers\EMetadatumController;

// routes for eMetadatum management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGapp')->group(function () {
        Route::get('eMetadata/getData', [EMetadatumController::class, 'getData'])->name('eMetadata.getData');
        // ✅ Route JSON
        Route::get('eMetadata/json/{id}', [EMetadatumController::class, 'getEMetadatum'])
            ->name('eMetadata.getById');
        // bulk - edit and delete
        Route::post('eMetadata/bulk-delete', [EMetadatumController::class, 'bulkDelete'])
        ->name('eMetadata.bulkDelete');
        Route::get('eMetadata/bulk-edit', [EMetadatumController::class, 'bulkEditForm'])
        ->name('eMetadata.bulkEdit');
        Route::post('eMetadata/bulk-update', [EMetadatumController::class, 'bulkUpdate'])
        ->name('eMetadata.bulkUpdate');

        Route::resource('eMetadata', EMetadatumController::class)
            ->parameters(['eMetadata' => 'eMetadatum']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('eMetadata/import', [EMetadatumController::class, 'import'])->name('eMetadata.import');
            Route::get('eMetadata/export/{format}', [EMetadatumController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('eMetadata.export');

        });

        Route::post('eMetadata/data-calcul', [EMetadatumController::class, 'dataCalcul'])->name('eMetadata.dataCalcul');
        Route::post('eMetadata/update-attributes', [EMetadatumController::class, 'updateAttributes'])->name('eMetadata.updateAttributes');

    

    });
});
