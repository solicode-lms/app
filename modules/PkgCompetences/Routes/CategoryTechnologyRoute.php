<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCompetences\Controllers\CategoryTechnologyController;

// routes for categoryTechnology management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCompetences')->group(function () {

        Route::get('categoryTechnologies/getCategoryTechnologies', [CategoryTechnologyController::class, 'getCategoryTechnologies'])->name('categoryTechnologies.all');
        Route::resource('categoryTechnologies', CategoryTechnologyController::class)
            ->parameters(['categoryTechnologies' => 'categoryTechnology']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('categoryTechnologies/import', [CategoryTechnologyController::class, 'import'])->name('categoryTechnologies.import');
            Route::get('categoryTechnologies/export/{format}', [CategoryTechnologyController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('categoryTechnologies.export');

        });

        Route::post('categoryTechnologies/data-calcul', [CategoryTechnologyController::class, 'dataCalcul'])->name('categoryTechnologies.dataCalcul');

    });
});
