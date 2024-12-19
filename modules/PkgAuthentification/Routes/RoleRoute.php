<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgAuthentification\Controllers\RoleController;

// routes for role management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgAuthentification')->group(function () {

        Route::get('roles/getRoles', [RoleController::class, 'getRoles'])->name('roles.all');
        Route::resource('roles', RoleController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('roles/export', [RoleController::class, 'export'])->name('roles.export');
            Route::post('roles/import', [RoleController::class, 'import'])->name('roles.import');
        });
    });
});
