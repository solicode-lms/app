<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgAutorisation\Controllers\PermissionController;

// routes for permission management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgAutorisation')->group(function () {

        Route::get('permissions/getData', [PermissionController::class, 'getData'])->name('permissions.getData');
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

    });
});
