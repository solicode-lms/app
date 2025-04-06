<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCompetences\Controllers\CompetenceController;

// routes for competence management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCompetences')->group(function () {

        Route::get('competences/getData', [CompetenceController::class, 'getData'])->name('competences.getData');
        Route::resource('competences', CompetenceController::class)
            ->parameters(['competences' => 'competence']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('competences/import', [CompetenceController::class, 'import'])->name('competences.import');
            Route::get('competences/export/{format}', [CompetenceController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('competences.export');

        });

        Route::post('competences/data-calcul', [CompetenceController::class, 'dataCalcul'])->name('competences.dataCalcul');
        Route::post('competences/update-attributes', [CompetenceController::class, 'updateAttributes'])->name('competences.updateAttributes');

    

    });
});
