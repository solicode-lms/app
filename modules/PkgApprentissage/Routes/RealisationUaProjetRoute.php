<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgApprentissage\Controllers\RealisationUaProjetController;

// routes for realisationUaProjet management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgApprentissage')->group(function () {

        // Edition inline
        Route::get('realisationUaProjets/{id}/field/{field}/meta', [RealisationUaProjetController::class, 'fieldMeta'])
            ->name('realisationUaProjets.field.meta');
        Route::patch('realisationUaProjets/{id}/inline', [RealisationUaProjetController::class, 'patchInline'])
            ->name('realisationUaProjets.patchInline');

        Route::get('realisationUaProjets/getData', [RealisationUaProjetController::class, 'getData'])->name('realisationUaProjets.getData');
        // ✅ Route JSON
        Route::get('realisationUaProjets/json/{id}', [RealisationUaProjetController::class, 'getRealisationUaProjet'])
            ->name('realisationUaProjets.getById');
        // bulk - edit and delete
        Route::post('realisationUaProjets/bulk-delete', [RealisationUaProjetController::class, 'bulkDelete'])
        ->name('realisationUaProjets.bulkDelete');
        Route::get('realisationUaProjets/bulk-edit', [RealisationUaProjetController::class, 'bulkEditForm'])
        ->name('realisationUaProjets.bulkEdit');
        Route::post('realisationUaProjets/bulk-update', [RealisationUaProjetController::class, 'bulkUpdate'])
        ->name('realisationUaProjets.bulkUpdate');

        Route::resource('realisationUaProjets', RealisationUaProjetController::class)
            ->parameters(['realisationUaProjets' => 'realisationUaProjet']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('realisationUaProjets/import', [RealisationUaProjetController::class, 'import'])->name('realisationUaProjets.import');
            Route::get('realisationUaProjets/export/{format}', [RealisationUaProjetController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('realisationUaProjets.export');

        });

        Route::post('realisationUaProjets/data-calcul', [RealisationUaProjetController::class, 'dataCalcul'])->name('realisationUaProjets.dataCalcul');
        Route::post('realisationUaProjets/update-attributes', [RealisationUaProjetController::class, 'updateAttributes'])->name('realisationUaProjets.updateAttributes');

    

    });
});
