<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgApprentissage\Controllers\EtatRealisationMicroCompetenceController;

// routes for etatRealisationMicroCompetence management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgApprentissage')->group(function () {
        Route::get('etatRealisationMicroCompetences/getData', [EtatRealisationMicroCompetenceController::class, 'getData'])->name('etatRealisationMicroCompetences.getData');
        // bulk - edit and delete
        Route::post('etatRealisationMicroCompetences/bulk-delete', [EtatRealisationMicroCompetenceController::class, 'bulkDelete'])
        ->name('etatRealisationMicroCompetences.bulkDelete');
        Route::get('etatRealisationMicroCompetences/bulk-edit', [EtatRealisationMicroCompetenceController::class, 'bulkEditForm'])
        ->name('etatRealisationMicroCompetences.bulkEdit');
        Route::post('etatRealisationMicroCompetences/bulk-update', [EtatRealisationMicroCompetenceController::class, 'bulkUpdate'])
        ->name('etatRealisationMicroCompetences.bulkUpdate');

        Route::resource('etatRealisationMicroCompetences', EtatRealisationMicroCompetenceController::class)
            ->parameters(['etatRealisationMicroCompetences' => 'etatRealisationMicroCompetence']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('etatRealisationMicroCompetences/import', [EtatRealisationMicroCompetenceController::class, 'import'])->name('etatRealisationMicroCompetences.import');
            Route::get('etatRealisationMicroCompetences/export/{format}', [EtatRealisationMicroCompetenceController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('etatRealisationMicroCompetences.export');

        });

        Route::post('etatRealisationMicroCompetences/data-calcul', [EtatRealisationMicroCompetenceController::class, 'dataCalcul'])->name('etatRealisationMicroCompetences.dataCalcul');
        Route::post('etatRealisationMicroCompetences/update-attributes', [EtatRealisationMicroCompetenceController::class, 'updateAttributes'])->name('etatRealisationMicroCompetences.updateAttributes');

    

    });
});
