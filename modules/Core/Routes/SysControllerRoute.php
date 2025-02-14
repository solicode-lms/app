<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\Core\Controllers\SysControllerController;

// routes for sysController management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/Core')->group(function () {

        Route::get('sysControllers/getSysControllers', [SysControllerController::class, 'getSysControllers'])->name('sysControllers.all');
        Route::resource('sysControllers', SysControllerController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('sysControllers/import', [SysControllerController::class, 'import'])->name('sysControllers.import');
            Route::get('sysControllers/export/{format}', [SysControllerController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('sysControllers.export');

        });

        Route::post('sysControllers/data-calcul', [SysControllerController::class, 'dataCalcul'])->name('sysControllers.dataCalcul');

    });
});
