<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgAutorisation\Controllers\RoleController;

// routes for role management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgAutorisation')->group(function () {

        Route::get('roles/getRoles', [RoleController::class, 'getRoles'])->name('roles.all');
        Route::resource('roles', RoleController::class)
            ->parameters(['roles' => 'role']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('roles/import', [RoleController::class, 'import'])->name('roles.import');
            Route::get('roles/export/{format}', [RoleController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('roles.export');

        });

        Route::post('roles/data-calcul', [RoleController::class, 'dataCalcul'])->name('roles.dataCalcul');

    });
});
