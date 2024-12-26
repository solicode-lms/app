<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCreationProjet\Controllers\TransfertCompetenceController;

// routes for transfertCompetence management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCreationProjet')->group(function () {

        Route::get('transfertCompetences/getTransfertCompetences', [TransfertCompetenceController::class, 'getTransfertCompetences'])->name('transfertCompetences.all');
        Route::resource('transfertCompetences', TransfertCompetenceController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('transfertCompetences/export', [TransfertCompetenceController::class, 'export'])->name('transfertCompetences.export');
            Route::post('transfertCompetences/import', [TransfertCompetenceController::class, 'import'])->name('transfertCompetences.import');
        });
    });
});
