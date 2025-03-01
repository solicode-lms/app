<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgRealisationProjets\Controllers\RealisationProjetController;

// routes for realisationProjet management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgRealisationProjets')->group(function () {

        Route::get('realisationProjets/getRealisationProjets', [RealisationProjetController::class, 'getRealisationProjets'])->name('realisationProjets.all');
        Route::resource('realisationProjets', RealisationProjetController::class)
            ->parameters(['realisationProjets' => 'realisationProjet']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('realisationProjets/import', [RealisationProjetController::class, 'import'])->name('realisationProjets.import');
            Route::get('realisationProjets/export/{format}', [RealisationProjetController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('realisationProjets.export');

        });

        Route::post('realisationProjets/data-calcul', [RealisationProjetController::class, 'dataCalcul'])->name('realisationProjets.dataCalcul');

    });
});
