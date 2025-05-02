<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCreationProjet\Controllers\ProjetController;

// routes for projet management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCreationProjet')->group(function () {
        Route::get('projets/getData', [ProjetController::class, 'getData'])->name('projets.getData');
        // bulk - edit and delete
        Route::post('projets/bulk-delete', [ProjetController::class, 'bulkDelete'])
        ->name('projets.bulkDelete');
        Route::get('projets/bulk-edit', [ProjetController::class, 'bulkEditForm'])
        ->name('projets.bulkEdit');
        Route::post('projets/bulk-update', [ProjetController::class, 'bulkUpdate'])
        ->name('projets.bulkUpdate');

        Route::resource('projets', ProjetController::class)
            ->parameters(['projets' => 'projet']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('projets/import', [ProjetController::class, 'import'])->name('projets.import');
            Route::get('projets/export/{format}', [ProjetController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('projets.export');

        });

        Route::post('projets/data-calcul', [ProjetController::class, 'dataCalcul'])->name('projets.dataCalcul');
        Route::post('projets/update-attributes', [ProjetController::class, 'updateAttributes'])->name('projets.updateAttributes');

    

    });
});
