<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCreationProjet\Controllers\LivrableController;

// routes for livrable management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCreationProjet')->group(function () {

        Route::get('livrables/getData', [LivrableController::class, 'getData'])->name('livrables.getData');
        // bulk - edit and delete
        Route::post('livrables/bulk-delete', [LivrableController::class, 'bulkDelete'])
        ->name('livrables.bulkDelete');
        Route::get('livrables/bulk-edit', [LivrableController::class, 'bulkEditForm'])
        ->name('livrables.bulkEdit');
        Route::post('livrables/bulk-update', [LivrableController::class, 'bulkUpdate'])
        ->name('livrables.bulkUpdate');

        Route::resource('livrables', LivrableController::class)
            ->parameters(['livrables' => 'livrable']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('livrables/import', [LivrableController::class, 'import'])->name('livrables.import');
            Route::get('livrables/export/{format}', [LivrableController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('livrables.export');

        });

        Route::post('livrables/data-calcul', [LivrableController::class, 'dataCalcul'])->name('livrables.dataCalcul');
        Route::post('livrables/update-attributes', [LivrableController::class, 'updateAttributes'])->name('livrables.updateAttributes');

    

    });
});
