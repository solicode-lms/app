<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCompetences\Controllers\NiveauCompetenceController;

// routes for niveauCompetence management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCompetences')->group(function () {

        Route::get('niveauCompetences/getData', [NiveauCompetenceController::class, 'getData'])->name('niveauCompetences.getData');
        Route::resource('niveauCompetences', NiveauCompetenceController::class)
            ->parameters(['niveauCompetences' => 'niveauCompetence']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('niveauCompetences/import', [NiveauCompetenceController::class, 'import'])->name('niveauCompetences.import');
            Route::get('niveauCompetences/export/{format}', [NiveauCompetenceController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('niveauCompetences.export');

        });

        Route::post('niveauCompetences/data-calcul', [NiveauCompetenceController::class, 'dataCalcul'])->name('niveauCompetences.dataCalcul');
        Route::post('niveauCompetences/update-attributes', [NiveauCompetenceController::class, 'updateAttributes'])->name('niveauCompetences.updateAttributes');

    

    });
});
