<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgApprentissage\Controllers\RealisationCompetenceController;

// routes for realisationCompetence management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgApprentissage')->group(function () {
        Route::get('realisationCompetences/getData', [RealisationCompetenceController::class, 'getData'])->name('realisationCompetences.getData');
        // ✅ Route JSON
        Route::get('realisationCompetences/json/{id}', [RealisationCompetenceController::class, 'getRealisationCompetence'])
            ->name('realisationCompetences.getById');
        // bulk - edit and delete
        Route::post('realisationCompetences/bulk-delete', [RealisationCompetenceController::class, 'bulkDelete'])
        ->name('realisationCompetences.bulkDelete');
        Route::get('realisationCompetences/bulk-edit', [RealisationCompetenceController::class, 'bulkEditForm'])
        ->name('realisationCompetences.bulkEdit');
        Route::post('realisationCompetences/bulk-update', [RealisationCompetenceController::class, 'bulkUpdate'])
        ->name('realisationCompetences.bulkUpdate');

        Route::resource('realisationCompetences', RealisationCompetenceController::class)
            ->parameters(['realisationCompetences' => 'realisationCompetence']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('realisationCompetences/import', [RealisationCompetenceController::class, 'import'])->name('realisationCompetences.import');
            Route::get('realisationCompetences/export/{format}', [RealisationCompetenceController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('realisationCompetences.export');

        });

        Route::post('realisationCompetences/data-calcul', [RealisationCompetenceController::class, 'dataCalcul'])->name('realisationCompetences.dataCalcul');
        Route::post('realisationCompetences/update-attributes', [RealisationCompetenceController::class, 'updateAttributes'])->name('realisationCompetences.updateAttributes');

    

    });
});
