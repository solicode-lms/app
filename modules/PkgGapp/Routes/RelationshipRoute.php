<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGapp\Controllers\RelationshipController;

// routes for relationship management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGapp')->group(function () {

        Route::get('relationships/getRelationships', [RelationshipController::class, 'getRelationships'])->name('relationships.all');
        Route::resource('relationships', RelationshipController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('relationships/export', [RelationshipController::class, 'export'])->name('relationships.export');
            Route::post('relationships/import', [RelationshipController::class, 'import'])->name('relationships.import');
        });
    });
});
