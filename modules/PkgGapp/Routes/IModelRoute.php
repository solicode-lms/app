<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGapp\Controllers\IModelController;

// routes for iModel management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGapp')->group(function () {

        Route::get('iModels/getIModels', [IModelController::class, 'getIModels'])->name('iModels.all');
        Route::resource('iModels', IModelController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('iModels/export', [IModelController::class, 'export'])->name('iModels.export');
            Route::post('iModels/import', [IModelController::class, 'import'])->name('iModels.import');
        });
    });
});
