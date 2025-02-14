<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgAutorisation\Controllers\PermissionController;

// routes for permission management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgAutorisation')->group(function () {

        Route::get('permissions/getPermissions', [PermissionController::class, 'getPermissions'])->name('permissions.all');
        Route::resource('permissions', PermissionController::class);
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
