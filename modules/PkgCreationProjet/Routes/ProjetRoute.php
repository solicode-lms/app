<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCreationProjet\Controllers\ProjetController;

// routes for projet management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCreationProjet')->group(function () {

        Route::get('projets/getProjets', [ProjetController::class, 'getProjets'])->name('projets.all');
        Route::resource('projets', ProjetController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('projets/export', [ProjetController::class, 'export'])->name('projets.export');
            Route::post('projets/import', [ProjetController::class, 'import'])->name('projets.import');
        });

        Route::post('projets/data-calcul', [ProjetController::class, 'dataCalcul'])->name('projets.dataCalcul');

    });
});
