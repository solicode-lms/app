<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCreationProjet\Controllers\TransfertCompetenceController;

// routes for transfertCompetence management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCreationProjet')->group(function () {

        Route::get('transfertCompetences/getData', [TransfertCompetenceController::class, 'getData'])->name('transfertCompetences.getData');
        // bulk - edit and delete
        Route::post('transfertCompetences/bulk-delete', [TransfertCompetenceController::class, 'bulkDelete'])
        ->name('transfertCompetences.bulkDelete');
        Route::get('transfertCompetences/bulk-edit', [TransfertCompetenceController::class, 'bulkEditForm'])
        ->name('transfertCompetences.bulkEdit');
        Route::post('transfertCompetences/bulk-update', [TransfertCompetenceController::class, 'bulkUpdate'])
        ->name('transfertCompetences.bulkUpdate');

        Route::resource('transfertCompetences', TransfertCompetenceController::class)
            ->parameters(['transfertCompetences' => 'transfertCompetence']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('transfertCompetences/import', [TransfertCompetenceController::class, 'import'])->name('transfertCompetences.import');
            Route::get('transfertCompetences/export/{format}', [TransfertCompetenceController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('transfertCompetences.export');

        });

        Route::post('transfertCompetences/data-calcul', [TransfertCompetenceController::class, 'dataCalcul'])->name('transfertCompetences.dataCalcul');
        Route::post('transfertCompetences/update-attributes', [TransfertCompetenceController::class, 'updateAttributes'])->name('transfertCompetences.updateAttributes');

    

    });
});
