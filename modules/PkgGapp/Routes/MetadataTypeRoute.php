<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGapp\Controllers\MetadataTypeController;

// routes for metadataType management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGapp')->group(function () {

        Route::get('metadataTypes/getMetadataTypes', [MetadataTypeController::class, 'getMetadataTypes'])->name('metadataTypes.all');
        Route::resource('metadataTypes', MetadataTypeController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('metadataTypes/export', [MetadataTypeController::class, 'export'])->name('metadataTypes.export');
            Route::post('metadataTypes/import', [MetadataTypeController::class, 'import'])->name('metadataTypes.import');
        });
    });
});
