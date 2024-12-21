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
            Route::get('permissions/export', [PermissionController::class, 'export'])->name('permissions.export');
            Route::post('permissions/import', [PermissionController::class, 'import'])->name('permissions.import');
        });
    });
});
