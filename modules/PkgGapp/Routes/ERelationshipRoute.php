<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGapp\Controllers\ERelationshipController;

// routes for eRelationship management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGapp')->group(function () {

        Route::get('eRelationships/getData', [ERelationshipController::class, 'getData'])->name('eRelationships.getData');
        Route::resource('eRelationships', ERelationshipController::class)
            ->parameters(['eRelationships' => 'eRelationship']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('eRelationships/import', [ERelationshipController::class, 'import'])->name('eRelationships.import');
            Route::get('eRelationships/export/{format}', [ERelationshipController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('eRelationships.export');

        });

        Route::post('eRelationships/data-calcul', [ERelationshipController::class, 'dataCalcul'])->name('eRelationships.dataCalcul');

    });
});
