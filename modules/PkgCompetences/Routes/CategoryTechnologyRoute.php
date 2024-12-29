<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCompetences\Controllers\CategoryTechnologyController;

// routes for categoryTechnology management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCompetences')->group(function () {

        Route::get('categoryTechnologies/getCategoryTechnologies', [CategoryTechnologyController::class, 'getCategoryTechnologies'])->name('categoryTechnologies.all');
        Route::resource('categoryTechnologies', CategoryTechnologyController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('categoryTechnologies/export', [CategoryTechnologyController::class, 'export'])->name('categoryTechnologies.export');
            Route::post('categoryTechnologies/import', [CategoryTechnologyController::class, 'import'])->name('categoryTechnologies.import');
        });
    });
});
