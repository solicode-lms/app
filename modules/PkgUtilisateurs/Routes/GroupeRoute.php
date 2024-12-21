<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgUtilisateurs\Controllers\GroupeController;

// routes for groupe management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgUtilisateurs')->group(function () {

        Route::get('groupes/getGroupes', [GroupeController::class, 'getGroupes'])->name('groupes.all');
        Route::resource('groupes', GroupeController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('groupes/export', [GroupeController::class, 'export'])->name('groupes.export');
            Route::post('groupes/import', [GroupeController::class, 'import'])->name('groupes.import');
        });
    });
});
