<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\Core\Controllers\FeatureDomainController;

// routes for featureDomain management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/Core')->group(function () {

        Route::get('featureDomains/getFeatureDomains', [FeatureDomainController::class, 'getFeatureDomains'])->name('featureDomains.all');
        Route::resource('featureDomains', FeatureDomainController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('featureDomains/export', [FeatureDomainController::class, 'export'])->name('featureDomains.export');
            Route::post('featureDomains/import', [FeatureDomainController::class, 'import'])->name('featureDomains.import');
        });
    });
});
