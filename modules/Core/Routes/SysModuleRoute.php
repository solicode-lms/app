<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\Core\Controllers\SysModuleController;

// routes for sysModule management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/Core')->group(function () {
        Route::get('sysModules/getData', [SysModuleController::class, 'getData'])->name('sysModules.getData');
        // ✅ Route JSON
        Route::get('sysModules/json/{id}', [SysModuleController::class, 'getSysModule'])
            ->name('sysModules.getById');
        // bulk - edit and delete
        Route::post('sysModules/bulk-delete', [SysModuleController::class, 'bulkDelete'])
        ->name('sysModules.bulkDelete');
        Route::get('sysModules/bulk-edit', [SysModuleController::class, 'bulkEditForm'])
        ->name('sysModules.bulkEdit');
        Route::post('sysModules/bulk-update', [SysModuleController::class, 'bulkUpdate'])
        ->name('sysModules.bulkUpdate');

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
        Route::post('sysModules/update-attributes', [SysModuleController::class, 'updateAttributes'])->name('sysModules.updateAttributes');

    

    });
});
