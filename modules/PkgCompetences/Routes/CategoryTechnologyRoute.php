<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCompetences\Controllers\CategoryTechnologyController;

// routes for categoryTechnology management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCompetences')->group(function () {

        Route::get('categoryTechnologies/getData', [CategoryTechnologyController::class, 'getData'])->name('categoryTechnologies.getData');
        // bulk - edit and delete
        Route::post('categoryTechnologies/bulk-delete', [CategoryTechnologyController::class, 'bulkDelete'])
        ->name('categoryTechnologies.bulkDelete');
        Route::get('categoryTechnologies/bulk-edit', [CategoryTechnologyController::class, 'bulkEditForm'])
        ->name('categoryTechnologies.bulkEdit');
        Route::post('categoryTechnologies/bulk-update', [CategoryTechnologyController::class, 'bulkUpdate'])
        ->name('categoryTechnologies.bulkUpdate');

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
        Route::post('categoryTechnologies/update-attributes', [CategoryTechnologyController::class, 'updateAttributes'])->name('categoryTechnologies.updateAttributes');

    

    });
});
