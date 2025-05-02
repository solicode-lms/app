<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\Core\Controllers\FeatureDomainController;

// routes for featureDomain management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/Core')->group(function () {
        Route::get('featureDomains/getData', [FeatureDomainController::class, 'getData'])->name('featureDomains.getData');
        // bulk - edit and delete
        Route::post('featureDomains/bulk-delete', [FeatureDomainController::class, 'bulkDelete'])
        ->name('featureDomains.bulkDelete');
        Route::get('featureDomains/bulk-edit', [FeatureDomainController::class, 'bulkEditForm'])
        ->name('featureDomains.bulkEdit');
        Route::post('featureDomains/bulk-update', [FeatureDomainController::class, 'bulkUpdate'])
        ->name('featureDomains.bulkUpdate');

        Route::resource('featureDomains', FeatureDomainController::class)
            ->parameters(['featureDomains' => 'featureDomain']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('featureDomains/import', [FeatureDomainController::class, 'import'])->name('featureDomains.import');
            Route::get('featureDomains/export/{format}', [FeatureDomainController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('featureDomains.export');

        });

        Route::post('featureDomains/data-calcul', [FeatureDomainController::class, 'dataCalcul'])->name('featureDomains.dataCalcul');
        Route::post('featureDomains/update-attributes', [FeatureDomainController::class, 'updateAttributes'])->name('featureDomains.updateAttributes');

    

    });
});
