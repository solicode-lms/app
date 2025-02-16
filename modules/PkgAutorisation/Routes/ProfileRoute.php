<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgAutorisation\Controllers\ProfileController;

// routes for profile management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgAutorisation')->group(function () {

        Route::get('profiles/getProfiles', [ProfileController::class, 'getProfiles'])->name('profiles.all');
        Route::resource('profiles', ProfileController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('profiles/import', [ProfileController::class, 'import'])->name('profiles.import');
            Route::get('profiles/export/{format}', [ProfileController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('profiles.export');

        });

        Route::post('profiles/data-calcul', [ProfileController::class, 'dataCalcul'])->name('profiles.dataCalcul');

    });
});
