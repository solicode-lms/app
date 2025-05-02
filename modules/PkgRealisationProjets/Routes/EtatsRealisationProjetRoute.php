<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgRealisationProjets\Controllers\EtatsRealisationProjetController;

// routes for etatsRealisationProjet management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgRealisationProjets')->group(function () {
        Route::get('etatsRealisationProjets/getData', [EtatsRealisationProjetController::class, 'getData'])->name('etatsRealisationProjets.getData');
        // bulk - edit and delete
        Route::post('etatsRealisationProjets/bulk-delete', [EtatsRealisationProjetController::class, 'bulkDelete'])
        ->name('etatsRealisationProjets.bulkDelete');
        Route::get('etatsRealisationProjets/bulk-edit', [EtatsRealisationProjetController::class, 'bulkEditForm'])
        ->name('etatsRealisationProjets.bulkEdit');
        Route::post('etatsRealisationProjets/bulk-update', [EtatsRealisationProjetController::class, 'bulkUpdate'])
        ->name('etatsRealisationProjets.bulkUpdate');

        Route::resource('etatsRealisationProjets', EtatsRealisationProjetController::class)
            ->parameters(['etatsRealisationProjets' => 'etatsRealisationProjet']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('etatsRealisationProjets/import', [EtatsRealisationProjetController::class, 'import'])->name('etatsRealisationProjets.import');
            Route::get('etatsRealisationProjets/export/{format}', [EtatsRealisationProjetController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('etatsRealisationProjets.export');

        });

        Route::post('etatsRealisationProjets/data-calcul', [EtatsRealisationProjetController::class, 'dataCalcul'])->name('etatsRealisationProjets.dataCalcul');
        Route::post('etatsRealisationProjets/update-attributes', [EtatsRealisationProjetController::class, 'updateAttributes'])->name('etatsRealisationProjets.updateAttributes');

    

    });
});
