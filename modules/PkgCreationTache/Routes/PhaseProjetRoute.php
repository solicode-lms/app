<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCreationTache\Controllers\PhaseProjetController;

// routes for phaseProjet management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCreationTache')->group(function () {

        // Edition inline
        Route::get('phaseProjets/{id}/field/{field}/meta', [PhaseProjetController::class, 'fieldMeta'])
            ->name('phaseProjets.field.meta');
        Route::patch('phaseProjets/{id}/inline', [PhaseProjetController::class, 'patchInline'])
            ->name('phaseProjets.patchInline');

        Route::get('phaseProjets/getData', [PhaseProjetController::class, 'getData'])->name('phaseProjets.getData');
        // ✅ Route JSON
        Route::get('phaseProjets/json/{id}', [PhaseProjetController::class, 'getPhaseProjet'])
            ->name('phaseProjets.getById');
        // bulk - edit and delete
        Route::post('phaseProjets/bulk-delete', [PhaseProjetController::class, 'bulkDelete'])
        ->name('phaseProjets.bulkDelete');
        Route::get('phaseProjets/bulk-edit', [PhaseProjetController::class, 'bulkEditForm'])
        ->name('phaseProjets.bulkEdit');
        Route::post('phaseProjets/bulk-update', [PhaseProjetController::class, 'bulkUpdate'])
        ->name('phaseProjets.bulkUpdate');

        Route::resource('phaseProjets', PhaseProjetController::class)
            ->parameters(['phaseProjets' => 'phaseProjet']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('phaseProjets/import', [PhaseProjetController::class, 'import'])->name('phaseProjets.import');
            Route::get('phaseProjets/export/{format}', [PhaseProjetController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('phaseProjets.export');

        });

        Route::post('phaseProjets/data-calcul', [PhaseProjetController::class, 'dataCalcul'])->name('phaseProjets.dataCalcul');
        Route::post('phaseProjets/update-attributes', [PhaseProjetController::class, 'updateAttributes'])->name('phaseProjets.updateAttributes');

    

    });
});
