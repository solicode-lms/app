<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCreationProjet\Controllers\LabelProjetController;

// routes for labelProjet management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCreationProjet')->group(function () {

        // Edition inline
        Route::get('labelProjets/{id}/field/{field}/meta', [LabelProjetController::class, 'fieldMeta'])
            ->name('labelProjets.field.meta');
        Route::patch('labelProjets/{id}/inline', [LabelProjetController::class, 'patchInline'])
            ->name('labelProjets.patchInline');

        Route::get('labelProjets/getData', [LabelProjetController::class, 'getData'])->name('labelProjets.getData');
        // ✅ Route JSON
        Route::get('labelProjets/json/{id}', [LabelProjetController::class, 'getLabelProjet'])
            ->name('labelProjets.getById');
        // bulk - edit and delete
        Route::post('labelProjets/bulk-delete', [LabelProjetController::class, 'bulkDelete'])
        ->name('labelProjets.bulkDelete');
        Route::get('labelProjets/bulk-edit', [LabelProjetController::class, 'bulkEditForm'])
        ->name('labelProjets.bulkEdit');
        Route::post('labelProjets/bulk-update', [LabelProjetController::class, 'bulkUpdate'])
        ->name('labelProjets.bulkUpdate');

        Route::resource('labelProjets', LabelProjetController::class)
            ->parameters(['labelProjets' => 'labelProjet']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('labelProjets/import', [LabelProjetController::class, 'import'])->name('labelProjets.import');
            Route::get('labelProjets/export/{format}', [LabelProjetController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('labelProjets.export');

        });

        Route::post('labelProjets/data-calcul', [LabelProjetController::class, 'dataCalcul'])->name('labelProjets.dataCalcul');
        Route::post('labelProjets/update-attributes', [LabelProjetController::class, 'updateAttributes'])->name('labelProjets.updateAttributes');

    

    });
});
