<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCompetences\Controllers\CategorieTechnologyController;

// routes for categorieTechnology management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCompetences')->group(function () {

        Route::get('categorieTechnologies/getCategorieTechnologies', [CategorieTechnologyController::class, 'getCategorieTechnologies'])->name('categorieTechnologies.all');
        Route::resource('categorieTechnologies', CategorieTechnologyController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('categorieTechnologies/export', [CategorieTechnologyController::class, 'export'])->name('categorieTechnologies.export');
            Route::post('categorieTechnologies/import', [CategorieTechnologyController::class, 'import'])->name('categorieTechnologies.import');
        });
    });
});
