<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgAutorisation\Controllers\RoleController;

// routes for role management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgAutorisation')->group(function () {

        // Edition inline
        Route::get('roles/{id}/field/{field}/meta', [RoleController::class, 'fieldMeta'])
            ->name('roles.field.meta');
        Route::patch('roles/{id}/inline', [RoleController::class, 'patchInline'])
            ->name('roles.patchInline');

        Route::get('roles/getData', [RoleController::class, 'getData'])->name('roles.getData');
        // ✅ Route JSON
        Route::get('roles/json/{id}', [RoleController::class, 'getRole'])
            ->name('roles.getById');
        // bulk - edit and delete
        Route::post('roles/bulk-delete', [RoleController::class, 'bulkDelete'])
        ->name('roles.bulkDelete');
        Route::get('roles/bulk-edit', [RoleController::class, 'bulkEditForm'])
        ->name('roles.bulkEdit');
        Route::post('roles/bulk-update', [RoleController::class, 'bulkUpdate'])
        ->name('roles.bulkUpdate');

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
        Route::post('roles/update-attributes', [RoleController::class, 'updateAttributes'])->name('roles.updateAttributes');

    

    });
});
