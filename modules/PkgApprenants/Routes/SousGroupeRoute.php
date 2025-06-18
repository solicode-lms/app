<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgApprenants\Controllers\SousGroupeController;

// routes for sousGroupe management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgApprenants')->group(function () {
        Route::get('sousGroupes/getData', [SousGroupeController::class, 'getData'])->name('sousGroupes.getData');
        // bulk - edit and delete
        Route::post('sousGroupes/bulk-delete', [SousGroupeController::class, 'bulkDelete'])
        ->name('sousGroupes.bulkDelete');
        Route::get('sousGroupes/bulk-edit', [SousGroupeController::class, 'bulkEditForm'])
        ->name('sousGroupes.bulkEdit');
        Route::post('sousGroupes/bulk-update', [SousGroupeController::class, 'bulkUpdate'])
        ->name('sousGroupes.bulkUpdate');

        Route::resource('sousGroupes', SousGroupeController::class)
            ->parameters(['sousGroupes' => 'sousGroupe']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('sousGroupes/import', [SousGroupeController::class, 'import'])->name('sousGroupes.import');
            Route::get('sousGroupes/export/{format}', [SousGroupeController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('sousGroupes.export');

        });

        Route::post('sousGroupes/data-calcul', [SousGroupeController::class, 'dataCalcul'])->name('sousGroupes.dataCalcul');
        Route::post('sousGroupes/update-attributes', [SousGroupeController::class, 'updateAttributes'])->name('sousGroupes.updateAttributes');

    

    });
});
