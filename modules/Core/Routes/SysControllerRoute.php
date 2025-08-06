<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\Core\Controllers\SysControllerController;

// routes for sysController management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/Core')->group(function () {
        Route::get('sysControllers/getData', [SysControllerController::class, 'getData'])->name('sysControllers.getData');
        // ✅ Route JSON
        Route::get('sysControllers/json/{id}', [SysControllerController::class, 'getSysController'])
            ->name('sysControllers.getById');
        // bulk - edit and delete
        Route::post('sysControllers/bulk-delete', [SysControllerController::class, 'bulkDelete'])
        ->name('sysControllers.bulkDelete');
        Route::get('sysControllers/bulk-edit', [SysControllerController::class, 'bulkEditForm'])
        ->name('sysControllers.bulkEdit');
        Route::post('sysControllers/bulk-update', [SysControllerController::class, 'bulkUpdate'])
        ->name('sysControllers.bulkUpdate');

        Route::resource('sysControllers', SysControllerController::class)
            ->parameters(['sysControllers' => 'sysController']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('sysControllers/import', [SysControllerController::class, 'import'])->name('sysControllers.import');
            Route::get('sysControllers/export/{format}', [SysControllerController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('sysControllers.export');

        });

        Route::post('sysControllers/data-calcul', [SysControllerController::class, 'dataCalcul'])->name('sysControllers.dataCalcul');
        Route::post('sysControllers/update-attributes', [SysControllerController::class, 'updateAttributes'])->name('sysControllers.updateAttributes');

    

    });
});
