<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\Core\Controllers\SysModuleController;

// routes for sysModule management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/Core')->group(function () {

        Route::get('sysModules/getSysModules', [SysModuleController::class, 'getSysModules'])->name('sysModules.all');
        Route::resource('sysModules', SysModuleController::class)
            ->parameters(['sysModules' => 'sysModule']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('sysModules/import', [SysModuleController::class, 'import'])->name('sysModules.import');
            Route::get('sysModules/export/{format}', [SysModuleController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('sysModules.export');

        });

        Route::post('sysModules/data-calcul', [SysModuleController::class, 'dataCalcul'])->name('sysModules.dataCalcul');

    });
});
