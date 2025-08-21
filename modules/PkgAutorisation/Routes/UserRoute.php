<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgAutorisation\Controllers\UserController;

// routes for user management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgAutorisation')->group(function () {

        // Edition inline
        Route::get('users/{id}/field/{field}/meta', [UserController::class, 'fieldMeta'])
            ->name('users.field.meta');
        Route::patch('users/{id}/inline', [UserController::class, 'patchInline'])
            ->name('users.patchInline');

        Route::get('users/getData', [UserController::class, 'getData'])->name('users.getData');
        // ✅ Route JSON
        Route::get('users/json/{id}', [UserController::class, 'getUser'])
            ->name('users.getById');
        // bulk - edit and delete
        Route::post('users/bulk-delete', [UserController::class, 'bulkDelete'])
        ->name('users.bulkDelete');
        Route::get('users/bulk-edit', [UserController::class, 'bulkEditForm'])
        ->name('users.bulkEdit');
        Route::post('users/bulk-update', [UserController::class, 'bulkUpdate'])
        ->name('users.bulkUpdate');

        Route::resource('users', UserController::class)
            ->parameters(['users' => 'user']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('users/import', [UserController::class, 'import'])->name('users.import');
            Route::get('users/export/{format}', [UserController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('users.export');

        });

        Route::post('users/data-calcul', [UserController::class, 'dataCalcul'])->name('users.dataCalcul');
        Route::post('users/update-attributes', [UserController::class, 'updateAttributes'])->name('users.updateAttributes');
        Route::get('users/initPassword/{id}', [UserController::class, 'initPassword'])->name('users.initPassword');
    
    

    });
});
