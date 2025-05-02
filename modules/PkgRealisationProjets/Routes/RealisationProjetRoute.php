<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgRealisationProjets\Controllers\RealisationProjetController;

// routes for realisationProjet management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgRealisationProjets')->group(function () {
        Route::get('realisationProjets/getData', [RealisationProjetController::class, 'getData'])->name('realisationProjets.getData');
        // bulk - edit and delete
        Route::post('realisationProjets/bulk-delete', [RealisationProjetController::class, 'bulkDelete'])
        ->name('realisationProjets.bulkDelete');
        Route::get('realisationProjets/bulk-edit', [RealisationProjetController::class, 'bulkEditForm'])
        ->name('realisationProjets.bulkEdit');
        Route::post('realisationProjets/bulk-update', [RealisationProjetController::class, 'bulkUpdate'])
        ->name('realisationProjets.bulkUpdate');

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
        Route::post('realisationProjets/update-attributes', [RealisationProjetController::class, 'updateAttributes'])->name('realisationProjets.updateAttributes');

    

    });
});
