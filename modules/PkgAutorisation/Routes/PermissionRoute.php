<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgAutorisation\Controllers\PermissionController;

// routes for permission management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgAutorisation')->group(function () {
        Route::get('permissions/getData', [PermissionController::class, 'getData'])->name('permissions.getData');
        // bulk - edit and delete
        Route::post('permissions/bulk-delete', [PermissionController::class, 'bulkDelete'])
        ->name('permissions.bulkDelete');
        Route::get('permissions/bulk-edit', [PermissionController::class, 'bulkEditForm'])
        ->name('permissions.bulkEdit');
        Route::post('permissions/bulk-update', [PermissionController::class, 'bulkUpdate'])
        ->name('permissions.bulkUpdate');

        Route::resource('permissions', PermissionController::class)
            ->parameters(['permissions' => 'permission']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('permissions/import', [PermissionController::class, 'import'])->name('permissions.import');
            Route::get('permissions/export/{format}', [PermissionController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('permissions.export');

        });

        Route::post('permissions/data-calcul', [PermissionController::class, 'dataCalcul'])->name('permissions.dataCalcul');
        Route::post('permissions/update-attributes', [PermissionController::class, 'updateAttributes'])->name('permissions.updateAttributes');

    

    });
});
