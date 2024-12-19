<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgUtilisateurs\Controllers\Niveaux_scolaireController;

// routes for niveaux_scolaire management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgUtilisateurs')->group(function () {

        Route::get('niveaux_scolaires/getNiveaux_scolaires', [Niveaux_scolaireController::class, 'getNiveaux_scolaires'])->name('niveaux_scolaires.all');
        Route::resource('niveaux_scolaires', Niveaux_scolaireController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('niveaux_scolaires/export', [Niveaux_scolaireController::class, 'export'])->name('niveaux_scolaires.export');
            Route::post('niveaux_scolaires/import', [Niveaux_scolaireController::class, 'import'])->name('niveaux_scolaires.import');
        });
    });
});
