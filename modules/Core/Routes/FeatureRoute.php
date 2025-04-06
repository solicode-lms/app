<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\Core\Controllers\FeatureController;

// routes for feature management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/Core')->group(function () {

        Route::get('features/getData', [FeatureController::class, 'getData'])->name('features.getData');
        Route::resource('features', FeatureController::class)
            ->parameters(['features' => 'feature']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('features/import', [FeatureController::class, 'import'])->name('features.import');
            Route::get('features/export/{format}', [FeatureController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('features.export');

        });

        Route::post('features/data-calcul', [FeatureController::class, 'dataCalcul'])->name('features.dataCalcul');
        Route::post('features/update-attributes', [FeatureController::class, 'updateAttributes'])->name('features.updateAttributes');

    

    });
});
