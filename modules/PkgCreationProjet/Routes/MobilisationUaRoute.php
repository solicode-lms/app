<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCreationProjet\Controllers\MobilisationUaController;

// routes for mobilisationUa management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCreationProjet')->group(function () {

        // Edition inline
        Route::get('mobilisationUas/{id}/field/{field}/meta', [MobilisationUaController::class, 'fieldMeta'])
            ->name('mobilisationUas.field.meta');
        Route::patch('mobilisationUas/{id}/inline', [MobilisationUaController::class, 'patchInline'])
            ->name('mobilisationUas.patchInline');

        Route::get('mobilisationUas/getData', [MobilisationUaController::class, 'getData'])->name('mobilisationUas.getData');
        // ✅ Route JSON
        Route::get('mobilisationUas/json/{id}', [MobilisationUaController::class, 'getMobilisationUa'])
            ->name('mobilisationUas.getById');
        // bulk - edit and delete
        Route::post('mobilisationUas/bulk-delete', [MobilisationUaController::class, 'bulkDelete'])
        ->name('mobilisationUas.bulkDelete');
        Route::get('mobilisationUas/bulk-edit', [MobilisationUaController::class, 'bulkEditForm'])
        ->name('mobilisationUas.bulkEdit');
        Route::post('mobilisationUas/bulk-update', [MobilisationUaController::class, 'bulkUpdate'])
        ->name('mobilisationUas.bulkUpdate');

        Route::resource('mobilisationUas', MobilisationUaController::class)
            ->parameters(['mobilisationUas' => 'mobilisationUa']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('mobilisationUas/import', [MobilisationUaController::class, 'import'])->name('mobilisationUas.import');
            Route::get('mobilisationUas/export/{format}', [MobilisationUaController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('mobilisationUas.export');

        });

        Route::post('mobilisationUas/data-calcul', [MobilisationUaController::class, 'dataCalcul'])->name('mobilisationUas.dataCalcul');
        Route::post('mobilisationUas/update-attributes', [MobilisationUaController::class, 'updateAttributes'])->name('mobilisationUas.updateAttributes');

    

    });
});
