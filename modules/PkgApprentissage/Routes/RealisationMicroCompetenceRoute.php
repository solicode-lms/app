<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgApprentissage\Controllers\RealisationMicroCompetenceController;

// routes for realisationMicroCompetence management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgApprentissage')->group(function () {
        Route::get('realisationMicroCompetences/getData', [RealisationMicroCompetenceController::class, 'getData'])->name('realisationMicroCompetences.getData');
        // ✅ Route JSON
        Route::get('realisationMicroCompetences/json/{id}', [RealisationMicroCompetenceController::class, 'getRealisationMicroCompetence'])
            ->name('realisationMicroCompetences.getById');
        // bulk - edit and delete
        Route::post('realisationMicroCompetences/bulk-delete', [RealisationMicroCompetenceController::class, 'bulkDelete'])
        ->name('realisationMicroCompetences.bulkDelete');
        Route::get('realisationMicroCompetences/bulk-edit', [RealisationMicroCompetenceController::class, 'bulkEditForm'])
        ->name('realisationMicroCompetences.bulkEdit');
        Route::post('realisationMicroCompetences/bulk-update', [RealisationMicroCompetenceController::class, 'bulkUpdate'])
        ->name('realisationMicroCompetences.bulkUpdate');

        Route::resource('realisationMicroCompetences', RealisationMicroCompetenceController::class)
            ->parameters(['realisationMicroCompetences' => 'realisationMicroCompetence']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('realisationMicroCompetences/import', [RealisationMicroCompetenceController::class, 'import'])->name('realisationMicroCompetences.import');
            Route::get('realisationMicroCompetences/export/{format}', [RealisationMicroCompetenceController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('realisationMicroCompetences.export');

        });

        Route::post('realisationMicroCompetences/data-calcul', [RealisationMicroCompetenceController::class, 'dataCalcul'])->name('realisationMicroCompetences.dataCalcul');
        Route::post('realisationMicroCompetences/update-attributes', [RealisationMicroCompetenceController::class, 'updateAttributes'])->name('realisationMicroCompetences.updateAttributes');

    

    });
});
