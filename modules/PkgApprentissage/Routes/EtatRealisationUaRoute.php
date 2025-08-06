<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgApprentissage\Controllers\EtatRealisationUaController;

// routes for etatRealisationUa management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgApprentissage')->group(function () {
        Route::get('etatRealisationUas/getData', [EtatRealisationUaController::class, 'getData'])->name('etatRealisationUas.getData');
        // ✅ Route JSON
        Route::get('etatRealisationUas/json/{id}', [EtatRealisationUaController::class, 'getEtatRealisationUa'])
            ->name('etatRealisationUas.getById');
        // bulk - edit and delete
        Route::post('etatRealisationUas/bulk-delete', [EtatRealisationUaController::class, 'bulkDelete'])
        ->name('etatRealisationUas.bulkDelete');
        Route::get('etatRealisationUas/bulk-edit', [EtatRealisationUaController::class, 'bulkEditForm'])
        ->name('etatRealisationUas.bulkEdit');
        Route::post('etatRealisationUas/bulk-update', [EtatRealisationUaController::class, 'bulkUpdate'])
        ->name('etatRealisationUas.bulkUpdate');

        Route::resource('etatRealisationUas', EtatRealisationUaController::class)
            ->parameters(['etatRealisationUas' => 'etatRealisationUa']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('etatRealisationUas/import', [EtatRealisationUaController::class, 'import'])->name('etatRealisationUas.import');
            Route::get('etatRealisationUas/export/{format}', [EtatRealisationUaController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('etatRealisationUas.export');

        });

        Route::post('etatRealisationUas/data-calcul', [EtatRealisationUaController::class, 'dataCalcul'])->name('etatRealisationUas.dataCalcul');
        Route::post('etatRealisationUas/update-attributes', [EtatRealisationUaController::class, 'updateAttributes'])->name('etatRealisationUas.updateAttributes');

    

    });
});
