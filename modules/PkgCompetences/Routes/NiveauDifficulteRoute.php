<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCompetences\Controllers\NiveauDifficulteController;

// routes for niveauDifficulte management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCompetences')->group(function () {

        Route::get('niveauDifficultes/getNiveauDifficultes', [NiveauDifficulteController::class, 'getNiveauDifficultes'])->name('niveauDifficultes.all');
        Route::resource('niveauDifficultes', NiveauDifficulteController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('niveauDifficultes/export', [NiveauDifficulteController::class, 'export'])->name('niveauDifficultes.export');
            Route::post('niveauDifficultes/import', [NiveauDifficulteController::class, 'import'])->name('niveauDifficultes.import');
        });
    });
});
