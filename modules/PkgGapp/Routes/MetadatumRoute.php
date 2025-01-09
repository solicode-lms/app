<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGapp\Controllers\MetadatumController;

// routes for metadatum management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGapp')->group(function () {

        Route::get('metadata/getMetadata', [MetadatumController::class, 'getMetadata'])->name('metadata.all');
        Route::resource('metadata', MetadatumController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('metadata/export', [MetadatumController::class, 'export'])->name('metadata.export');
            Route::post('metadata/import', [MetadatumController::class, 'import'])->name('metadata.import');
        });
    });
});
