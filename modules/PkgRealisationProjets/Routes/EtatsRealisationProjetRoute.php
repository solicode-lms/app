<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgRealisationProjets\Controllers\EtatsRealisationProjetController;

// routes for etatsRealisationProjet management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgRealisationProjets')->group(function () {

        Route::get('etatsRealisationProjets/getEtatsRealisationProjets', [EtatsRealisationProjetController::class, 'getEtatsRealisationProjets'])->name('etatsRealisationProjets.all');
        Route::resource('etatsRealisationProjets', EtatsRealisationProjetController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('etatsRealisationProjets/export', [EtatsRealisationProjetController::class, 'export'])->name('etatsRealisationProjets.export');
            Route::post('etatsRealisationProjets/import', [EtatsRealisationProjetController::class, 'import'])->name('etatsRealisationProjets.import');
        });
    });
});
