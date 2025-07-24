<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgApprentissage\Controllers\RealisationUaController;

// routes for realisationUa management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgApprentissage')->group(function () {
        Route::get('realisationUas/getData', [RealisationUaController::class, 'getData'])->name('realisationUas.getData');
        // bulk - edit and delete
        Route::post('realisationUas/bulk-delete', [RealisationUaController::class, 'bulkDelete'])
        ->name('realisationUas.bulkDelete');
        Route::get('realisationUas/bulk-edit', [RealisationUaController::class, 'bulkEditForm'])
        ->name('realisationUas.bulkEdit');
        Route::post('realisationUas/bulk-update', [RealisationUaController::class, 'bulkUpdate'])
        ->name('realisationUas.bulkUpdate');

        Route::resource('realisationUas', RealisationUaController::class)
            ->parameters(['realisationUas' => 'realisationUa']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('realisationUas/import', [RealisationUaController::class, 'import'])->name('realisationUas.import');
            Route::get('realisationUas/export/{format}', [RealisationUaController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('realisationUas.export');

        });

        Route::post('realisationUas/data-calcul', [RealisationUaController::class, 'dataCalcul'])->name('realisationUas.dataCalcul');
        Route::post('realisationUas/update-attributes', [RealisationUaController::class, 'updateAttributes'])->name('realisationUas.updateAttributes');

    

    });
});
