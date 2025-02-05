<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGapp\Controllers\EMetadataDefinitionController;

// routes for eMetadataDefinition management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGapp')->group(function () {

        Route::get('eMetadataDefinitions/getEMetadataDefinitions', [EMetadataDefinitionController::class, 'getEMetadataDefinitions'])->name('eMetadataDefinitions.all');
        Route::resource('eMetadataDefinitions', EMetadataDefinitionController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('eMetadataDefinitions/export', [EMetadataDefinitionController::class, 'export'])->name('eMetadataDefinitions.export');
            Route::post('eMetadataDefinitions/import', [EMetadataDefinitionController::class, 'import'])->name('eMetadataDefinitions.import');
        });

        Route::post('eMetadataDefinitions/data-calcul', [EMetadataDefinitionController::class, 'dataCalcul'])->name('eMetadataDefinitions.dataCalcul');

    });
});
