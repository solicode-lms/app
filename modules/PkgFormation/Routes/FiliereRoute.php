<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgFormation\Controllers\FiliereController;

// routes for filiere management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgFormation')->group(function () {

        Route::get('filieres/getFilieres', [FiliereController::class, 'getFilieres'])->name('filieres.all');
        Route::resource('filieres', FiliereController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('filieres/export', [FiliereController::class, 'export'])->name('filieres.export');
            Route::post('filieres/import', [FiliereController::class, 'import'])->name('filieres.import');
        });
    });
});
