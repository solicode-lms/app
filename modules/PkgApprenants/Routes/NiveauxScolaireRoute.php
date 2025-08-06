<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgApprenants\Controllers\NiveauxScolaireController;

// routes for niveauxScolaire management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgApprenants')->group(function () {
        Route::get('niveauxScolaires/getData', [NiveauxScolaireController::class, 'getData'])->name('niveauxScolaires.getData');
        // ✅ Route JSON
        Route::get('niveauxScolaires/json/{id}', [NiveauxScolaireController::class, 'getNiveauxScolaire'])
            ->name('niveauxScolaires.getById');
        // bulk - edit and delete
        Route::post('niveauxScolaires/bulk-delete', [NiveauxScolaireController::class, 'bulkDelete'])
        ->name('niveauxScolaires.bulkDelete');
        Route::get('niveauxScolaires/bulk-edit', [NiveauxScolaireController::class, 'bulkEditForm'])
        ->name('niveauxScolaires.bulkEdit');
        Route::post('niveauxScolaires/bulk-update', [NiveauxScolaireController::class, 'bulkUpdate'])
        ->name('niveauxScolaires.bulkUpdate');

        Route::resource('niveauxScolaires', NiveauxScolaireController::class)
            ->parameters(['niveauxScolaires' => 'niveauxScolaire']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('niveauxScolaires/import', [NiveauxScolaireController::class, 'import'])->name('niveauxScolaires.import');
            Route::get('niveauxScolaires/export/{format}', [NiveauxScolaireController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('niveauxScolaires.export');

        });

        Route::post('niveauxScolaires/data-calcul', [NiveauxScolaireController::class, 'dataCalcul'])->name('niveauxScolaires.dataCalcul');
        Route::post('niveauxScolaires/update-attributes', [NiveauxScolaireController::class, 'updateAttributes'])->name('niveauxScolaires.updateAttributes');

    

    });
});
