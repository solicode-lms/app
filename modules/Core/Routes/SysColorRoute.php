<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\Core\Controllers\SysColorController;

// routes for sysColor management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/Core')->group(function () {

        Route::get('sysColors/getSysColors', [SysColorController::class, 'getSysColors'])->name('sysColors.all');
        Route::resource('sysColors', SysColorController::class)
            ->parameters(['sysColors' => 'sysColor']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('sysColors/import', [SysColorController::class, 'import'])->name('sysColors.import');
            Route::get('sysColors/export/{format}', [SysColorController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('sysColors.export');

        });

        Route::post('sysColors/data-calcul', [SysColorController::class, 'dataCalcul'])->name('sysColors.dataCalcul');

    });
});
