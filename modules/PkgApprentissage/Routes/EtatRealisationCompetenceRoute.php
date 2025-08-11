<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgApprentissage\Controllers\EtatRealisationCompetenceController;

// routes for etatRealisationCompetence management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgApprentissage')->group(function () {
        Route::get('etatRealisationCompetences/getData', [EtatRealisationCompetenceController::class, 'getData'])->name('etatRealisationCompetences.getData');
        // ✅ Route JSON
        Route::get('etatRealisationCompetences/json/{id}', [EtatRealisationCompetenceController::class, 'getEtatRealisationCompetence'])
            ->name('etatRealisationCompetences.getById');
        // bulk - edit and delete
        Route::post('etatRealisationCompetences/bulk-delete', [EtatRealisationCompetenceController::class, 'bulkDelete'])
        ->name('etatRealisationCompetences.bulkDelete');
        Route::get('etatRealisationCompetences/bulk-edit', [EtatRealisationCompetenceController::class, 'bulkEditForm'])
        ->name('etatRealisationCompetences.bulkEdit');
        Route::post('etatRealisationCompetences/bulk-update', [EtatRealisationCompetenceController::class, 'bulkUpdate'])
        ->name('etatRealisationCompetences.bulkUpdate');

        Route::resource('etatRealisationCompetences', EtatRealisationCompetenceController::class)
            ->parameters(['etatRealisationCompetences' => 'etatRealisationCompetence']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('etatRealisationCompetences/import', [EtatRealisationCompetenceController::class, 'import'])->name('etatRealisationCompetences.import');
            Route::get('etatRealisationCompetences/export/{format}', [EtatRealisationCompetenceController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('etatRealisationCompetences.export');

        });

        Route::post('etatRealisationCompetences/data-calcul', [EtatRealisationCompetenceController::class, 'dataCalcul'])->name('etatRealisationCompetences.dataCalcul');
        Route::post('etatRealisationCompetences/update-attributes', [EtatRealisationCompetenceController::class, 'updateAttributes'])->name('etatRealisationCompetences.updateAttributes');

    

    });
});
