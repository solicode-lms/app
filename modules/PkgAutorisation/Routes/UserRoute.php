<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgAutorisation\Controllers\UserController;

// routes for user management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgAutorisation')->group(function () {

        Route::get('users/getData', [UserController::class, 'getData'])->name('users.getData');
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
        Route::get('users/initPassword/{id}', [UserController::class, 'initPassword'])->name('users.initPassword');
    
    });
});
