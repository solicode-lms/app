<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCompetences\Controllers\NiveauDifficulteController;

// routes for niveauDifficulte management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCompetences')->group(function () {

        Route::get('niveauDifficultes/getNiveauDifficultes', [NiveauDifficulteController::class, 'getNiveauDifficultes'])->name('niveauDifficultes.all');
        Route::resource('niveauDifficultes', NiveauDifficulteController::class)
            ->parameters(['niveauDifficultes' => 'niveauDifficulte']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('niveauDifficultes/import', [NiveauDifficulteController::class, 'import'])->name('niveauDifficultes.import');
            Route::get('niveauDifficultes/export/{format}', [NiveauDifficulteController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('niveauDifficultes.export');

        });

        Route::post('niveauDifficultes/data-calcul', [NiveauDifficulteController::class, 'dataCalcul'])->name('niveauDifficultes.dataCalcul');

    });
});
