<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCompetences\Controllers\NiveauDifficulteController;

// routes for niveauDifficulte management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCompetences')->group(function () {
        Route::get('niveauDifficultes/getData', [NiveauDifficulteController::class, 'getData'])->name('niveauDifficultes.getData');
        // bulk - edit and delete
        Route::post('niveauDifficultes/bulk-delete', [NiveauDifficulteController::class, 'bulkDelete'])
        ->name('niveauDifficultes.bulkDelete');
        Route::get('niveauDifficultes/bulk-edit', [NiveauDifficulteController::class, 'bulkEditForm'])
        ->name('niveauDifficultes.bulkEdit');
        Route::post('niveauDifficultes/bulk-update', [NiveauDifficulteController::class, 'bulkUpdate'])
        ->name('niveauDifficultes.bulkUpdate');

        Route::resource('niveauDifficultes', NiveauDifficulteController::class)
            ->parameters(['niveauDifficultes' => 'niveauDifficulte']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('niveauDifficultes/import', [NiveauDifficulteController::class, 'import'])->name('niveauDifficultes.import');
            Route::get('niveauDifficultes/export/{format}', [NiveauDifficulteController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('niveauDifficultes.export');

        });

        Route::post('niveauDifficultes/data-calcul', [NiveauDifficulteController::class, 'dataCalcul'])->name('niveauDifficultes.dataCalcul');
        Route::post('niveauDifficultes/update-attributes', [NiveauDifficulteController::class, 'updateAttributes'])->name('niveauDifficultes.updateAttributes');

    

    });
});
