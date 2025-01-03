<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCompetences\Controllers\ModuleController;

// routes for module management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCompetences')->group(function () {

        Route::get('modules/getModules', [ModuleController::class, 'getModules'])->name('modules.all');
        Route::resource('modules', ModuleController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('modules/export', [ModuleController::class, 'export'])->name('modules.export');
            Route::post('modules/import', [ModuleController::class, 'import'])->name('modules.import');
        });
    });
});
