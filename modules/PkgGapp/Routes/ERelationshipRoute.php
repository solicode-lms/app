<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGapp\Controllers\ERelationshipController;

// routes for eRelationship management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGapp')->group(function () {

        Route::get('eRelationships/getERelationships', [ERelationshipController::class, 'getERelationships'])->name('eRelationships.all');
        Route::resource('eRelationships', ERelationshipController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('eRelationships/export', [ERelationshipController::class, 'export'])->name('eRelationships.export');
            Route::post('eRelationships/import', [ERelationshipController::class, 'import'])->name('eRelationships.import');
        });
    });
});
