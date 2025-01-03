<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCompetences\Controllers\CompetenceController;

// routes for competence management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCompetences')->group(function () {

        Route::get('competences/getCompetences', [CompetenceController::class, 'getCompetences'])->name('competences.all');
        Route::resource('competences', CompetenceController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('competences/export', [CompetenceController::class, 'export'])->name('competences.export');
            Route::post('competences/import', [CompetenceController::class, 'import'])->name('competences.import');
        });
    });
});
