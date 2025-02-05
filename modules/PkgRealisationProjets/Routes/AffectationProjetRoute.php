<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgRealisationProjets\Controllers\AffectationProjetController;

// routes for affectationProjet management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgRealisationProjets')->group(function () {

        Route::get('affectationProjets/getAffectationProjets', [AffectationProjetController::class, 'getAffectationProjets'])->name('affectationProjets.all');
        Route::resource('affectationProjets', AffectationProjetController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('affectationProjets/export', [AffectationProjetController::class, 'export'])->name('affectationProjets.export');
            Route::post('affectationProjets/import', [AffectationProjetController::class, 'import'])->name('affectationProjets.import');
        });

        Route::post('affectationProjets/data-calcul', [AffectationProjetController::class, 'dataCalcul'])->name('affectationProjets.dataCalcul');

    });
});
