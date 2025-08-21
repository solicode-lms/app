<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCreationProjet\Controllers\NatureLivrableController;

// routes for natureLivrable management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCreationProjet')->group(function () {

        // Edition inline
        Route::get('natureLivrables/{id}/field/{field}/meta', [NatureLivrableController::class, 'fieldMeta'])
            ->name('natureLivrables.field.meta');
        Route::patch('natureLivrables/{id}/inline', [NatureLivrableController::class, 'patchInline'])
            ->name('natureLivrables.patchInline');

        Route::get('natureLivrables/getData', [NatureLivrableController::class, 'getData'])->name('natureLivrables.getData');
        // ✅ Route JSON
        Route::get('natureLivrables/json/{id}', [NatureLivrableController::class, 'getNatureLivrable'])
            ->name('natureLivrables.getById');
        // bulk - edit and delete
        Route::post('natureLivrables/bulk-delete', [NatureLivrableController::class, 'bulkDelete'])
        ->name('natureLivrables.bulkDelete');
        Route::get('natureLivrables/bulk-edit', [NatureLivrableController::class, 'bulkEditForm'])
        ->name('natureLivrables.bulkEdit');
        Route::post('natureLivrables/bulk-update', [NatureLivrableController::class, 'bulkUpdate'])
        ->name('natureLivrables.bulkUpdate');

        Route::resource('natureLivrables', NatureLivrableController::class)
            ->parameters(['natureLivrables' => 'natureLivrable']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('natureLivrables/import', [NatureLivrableController::class, 'import'])->name('natureLivrables.import');
            Route::get('natureLivrables/export/{format}', [NatureLivrableController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('natureLivrables.export');

        });

        Route::post('natureLivrables/data-calcul', [NatureLivrableController::class, 'dataCalcul'])->name('natureLivrables.dataCalcul');
        Route::post('natureLivrables/update-attributes', [NatureLivrableController::class, 'updateAttributes'])->name('natureLivrables.updateAttributes');

    

    });
});
