<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCreationProjet\Controllers\EquipeProjetController;

// routes for equipeProjet management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCreationProjet')->group(function () {

        // Edition inline
        Route::get('equipeProjets/{id}/field/{field}/meta', [EquipeProjetController::class, 'fieldMeta'])
            ->name('equipeProjets.field.meta');
        Route::patch('equipeProjets/{id}/inline', [EquipeProjetController::class, 'patchInline'])
            ->name('equipeProjets.patchInline');

        Route::get('equipeProjets/getData', [EquipeProjetController::class, 'getData'])->name('equipeProjets.getData');
        // ✅ Route JSON
        Route::get('equipeProjets/json/{id}', [EquipeProjetController::class, 'getEquipeProjet'])
            ->name('equipeProjets.getById');
        // bulk - edit and delete
        Route::post('equipeProjets/bulk-delete', [EquipeProjetController::class, 'bulkDelete'])
        ->name('equipeProjets.bulkDelete');
        Route::get('equipeProjets/bulk-edit', [EquipeProjetController::class, 'bulkEditForm'])
        ->name('equipeProjets.bulkEdit');
        Route::post('equipeProjets/bulk-update', [EquipeProjetController::class, 'bulkUpdate'])
        ->name('equipeProjets.bulkUpdate');

        Route::resource('equipeProjets', EquipeProjetController::class)
            ->parameters(['equipeProjets' => 'equipeProjet']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('equipeProjets/import', [EquipeProjetController::class, 'import'])->name('equipeProjets.import');
            Route::get('equipeProjets/export/{format}', [EquipeProjetController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('equipeProjets.export');

        });

        Route::post('equipeProjets/data-calcul', [EquipeProjetController::class, 'dataCalcul'])->name('equipeProjets.dataCalcul');
        Route::post('equipeProjets/update-attributes', [EquipeProjetController::class, 'updateAttributes'])->name('equipeProjets.updateAttributes');

    

    });
});
