<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgRealisationProjets\Controllers\AffectationProjetController;

// routes for affectationProjet management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgRealisationProjets')->group(function () {

        Route::get('affectationProjets/getData', [AffectationProjetController::class, 'getData'])->name('affectationProjets.getData');
        Route::resource('affectationProjets', AffectationProjetController::class)
            ->parameters(['affectationProjets' => 'affectationProjet']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('affectationProjets/import', [AffectationProjetController::class, 'import'])->name('affectationProjets.import');
            Route::get('affectationProjets/export/{format}', [AffectationProjetController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('affectationProjets.export');

        });

        Route::post('affectationProjets/data-calcul', [AffectationProjetController::class, 'dataCalcul'])->name('affectationProjets.dataCalcul');
        Route::post('affectationProjets/update-attributes', [AffectationProjetController::class, 'updateAttributes'])->name('affectationProjets.updateAttributes');

    

    });
});
