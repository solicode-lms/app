<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgUtilisateurs\Controllers\NiveauxScolaireController;

// routes for niveauxScolaire management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgUtilisateurs')->group(function () {

        Route::get('niveauxScolaires/getNiveauxScolaires', [NiveauxScolaireController::class, 'getNiveauxScolaires'])->name('niveauxScolaires.all');
        Route::resource('niveauxScolaires', NiveauxScolaireController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('niveauxScolaires/export', [NiveauxScolaireController::class, 'export'])->name('niveauxScolaires.export');
            Route::post('niveauxScolaires/import', [NiveauxScolaireController::class, 'import'])->name('niveauxScolaires.import');
        });
    });
});
