<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgApprenants\Controllers\ApprenantController;

// routes for apprenant management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgApprenants')->group(function () {

        Route::get('apprenants/getData', [ApprenantController::class, 'getData'])->name('apprenants.getData');
        // bulk - edit and delete
        Route::post('apprenants/bulk-delete', [ApprenantController::class, 'bulkDelete'])
        ->name('apprenants.bulkDelete');
        Route::get('apprenants/bulk-edit', [ApprenantController::class, 'bulkEditForm'])
        ->name('apprenants.bulkEdit');
        Route::post('apprenants/bulk-update', [ApprenantController::class, 'bulkUpdate'])
        ->name('apprenants.bulkUpdate');

        Route::resource('apprenants', ApprenantController::class)
            ->parameters(['apprenants' => 'apprenant']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('apprenants/import', [ApprenantController::class, 'import'])->name('apprenants.import');
            Route::get('apprenants/export/{format}', [ApprenantController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('apprenants.export');

        });

        Route::post('apprenants/data-calcul', [ApprenantController::class, 'dataCalcul'])->name('apprenants.dataCalcul');
        Route::post('apprenants/update-attributes', [ApprenantController::class, 'updateAttributes'])->name('apprenants.updateAttributes');
        Route::get('apprenants/initPassword/{id}', [ApprenantController::class, 'initPassword'])->name('apprenants.initPassword');
    
    

    });
});
