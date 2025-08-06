<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\Core\Controllers\SysModelController;

// routes for sysModel management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/Core')->group(function () {
        Route::get('sysModels/getData', [SysModelController::class, 'getData'])->name('sysModels.getData');
        // ✅ Route JSON
        Route::get('sysModels/json/{id}', [SysModelController::class, 'getSysModel'])
            ->name('sysModels.getById');
        // bulk - edit and delete
        Route::post('sysModels/bulk-delete', [SysModelController::class, 'bulkDelete'])
        ->name('sysModels.bulkDelete');
        Route::get('sysModels/bulk-edit', [SysModelController::class, 'bulkEditForm'])
        ->name('sysModels.bulkEdit');
        Route::post('sysModels/bulk-update', [SysModelController::class, 'bulkUpdate'])
        ->name('sysModels.bulkUpdate');

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
        Route::post('sysModels/update-attributes', [SysModelController::class, 'updateAttributes'])->name('sysModels.updateAttributes');

    

    });
});
