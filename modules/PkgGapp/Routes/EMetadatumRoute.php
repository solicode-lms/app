<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGapp\Controllers\EMetadatumController;

// routes for eMetadatum management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGapp')->group(function () {

        Route::get('eMetadata/getEMetadata', [EMetadatumController::class, 'getEMetadata'])->name('eMetadata.all');
        Route::resource('eMetadata', EMetadatumController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('eMetadata/export', [EMetadatumController::class, 'export'])->name('eMetadata.export');
            Route::post('eMetadata/import', [EMetadatumController::class, 'import'])->name('eMetadata.import');
        });
    });
});
