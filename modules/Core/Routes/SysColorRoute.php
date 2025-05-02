<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\Core\Controllers\SysColorController;

// routes for sysColor management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/Core')->group(function () {
        Route::get('sysColors/getData', [SysColorController::class, 'getData'])->name('sysColors.getData');
        // bulk - edit and delete
        Route::post('sysColors/bulk-delete', [SysColorController::class, 'bulkDelete'])
        ->name('sysColors.bulkDelete');
        Route::get('sysColors/bulk-edit', [SysColorController::class, 'bulkEditForm'])
        ->name('sysColors.bulkEdit');
        Route::post('sysColors/bulk-update', [SysColorController::class, 'bulkUpdate'])
        ->name('sysColors.bulkUpdate');

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
        Route::post('sysColors/update-attributes', [SysColorController::class, 'updateAttributes'])->name('sysColors.updateAttributes');

    

    });
});
