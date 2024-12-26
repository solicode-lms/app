<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCompetences\Controllers\AppreciationController;

// routes for appreciation management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCompetences')->group(function () {

        Route::get('appreciations/getAppreciations', [AppreciationController::class, 'getAppreciations'])->name('appreciations.all');
        Route::resource('appreciations', AppreciationController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('appreciations/export', [AppreciationController::class, 'export'])->name('appreciations.export');
            Route::post('appreciations/import', [AppreciationController::class, 'import'])->name('appreciations.import');
        });
    });
});
