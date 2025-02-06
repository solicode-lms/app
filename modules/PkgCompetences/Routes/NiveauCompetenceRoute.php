<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCompetences\Controllers\NiveauCompetenceController;

// routes for niveauCompetence management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCompetences')->group(function () {

        Route::get('niveauCompetences/getNiveauCompetences', [NiveauCompetenceController::class, 'getNiveauCompetences'])->name('niveauCompetences.all');
        Route::resource('niveauCompetences', NiveauCompetenceController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('niveauCompetences/export', [NiveauCompetenceController::class, 'export'])->name('niveauCompetences.export');
            Route::post('niveauCompetences/import', [NiveauCompetenceController::class, 'import'])->name('niveauCompetences.import');
        });

        Route::post('niveauCompetences/data-calcul', [NiveauCompetenceController::class, 'dataCalcul'])->name('niveauCompetences.dataCalcul');

    });
});
