<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgFormation\Controllers\ModuleController;

// routes for module management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgFormation')->group(function () {

        Route::get('modules/getData', [ModuleController::class, 'getData'])->name('modules.getData');
        Route::resource('modules', ModuleController::class)
            ->parameters(['modules' => 'module']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('modules/import', [ModuleController::class, 'import'])->name('modules.import');
            Route::get('modules/export/{format}', [ModuleController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('modules.export');

        });

        Route::post('modules/data-calcul', [ModuleController::class, 'dataCalcul'])->name('modules.dataCalcul');
        Route::post('modules/update-attributes', [ModuleController::class, 'updateAttributes'])->name('modules.updateAttributes');

    

    });
});
