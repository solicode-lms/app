<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGapp\Controllers\EMetadataDefinitionController;

// routes for eMetadataDefinition management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGapp')->group(function () {

        Route::get('eMetadataDefinitions/getData', [EMetadataDefinitionController::class, 'getData'])->name('eMetadataDefinitions.getData');
        Route::resource('eMetadataDefinitions', EMetadataDefinitionController::class)
            ->parameters(['eMetadataDefinitions' => 'eMetadataDefinition']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('eMetadataDefinitions/import', [EMetadataDefinitionController::class, 'import'])->name('eMetadataDefinitions.import');
            Route::get('eMetadataDefinitions/export/{format}', [EMetadataDefinitionController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('eMetadataDefinitions.export');

        });

        Route::post('eMetadataDefinitions/data-calcul', [EMetadataDefinitionController::class, 'dataCalcul'])->name('eMetadataDefinitions.dataCalcul');

    });
});
