<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgAutorisation\Controllers\UserController;

// routes for user management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgAutorisation')->group(function () {

        Route::get('users/getUsers', [UserController::class, 'getUsers'])->name('users.all');
        Route::resource('users', UserController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('users/export', [UserController::class, 'export'])->name('users.export');
            Route::post('users/import', [UserController::class, 'import'])->name('users.import');
        });
    });
});
