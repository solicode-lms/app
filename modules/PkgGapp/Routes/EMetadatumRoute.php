<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGapp\Controllers\EMetadatumController;

// routes for eMetadatum management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGapp')->group(function () {

        Route::get('eMetadata/getData', [EMetadatumController::class, 'getData'])->name('eMetadata.getData');
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
