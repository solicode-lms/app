<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\Core\Controllers\SysModelController;

// routes for sysModel management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/Core')->group(function () {

        Route::get('sysModels/getSysModels', [SysModelController::class, 'getSysModels'])->name('sysModels.all');
        Route::resource('sysModels', SysModelController::class)
            ->parameters(['sysModels' => 'sysModel']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('sysModels/import', [SysModelController::class, 'import'])->name('sysModels.import');
            Route::get('sysModels/export/{format}', [SysModelController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('sysModels.export');

        });

        Route::post('sysModels/data-calcul', [SysModelController::class, 'dataCalcul'])->name('sysModels.dataCalcul');

    });
});
