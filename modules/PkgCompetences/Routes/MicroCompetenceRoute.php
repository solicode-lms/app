<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCompetences\Controllers\MicroCompetenceController;

// routes for microCompetence management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCompetences')->group(function () {
        Route::get('microCompetences/getData', [MicroCompetenceController::class, 'getData'])->name('microCompetences.getData');
        // bulk - edit and delete
        Route::post('microCompetences/bulk-delete', [MicroCompetenceController::class, 'bulkDelete'])
        ->name('microCompetences.bulkDelete');
        Route::get('microCompetences/bulk-edit', [MicroCompetenceController::class, 'bulkEditForm'])
        ->name('microCompetences.bulkEdit');
        Route::post('microCompetences/bulk-update', [MicroCompetenceController::class, 'bulkUpdate'])
        ->name('microCompetences.bulkUpdate');

        Route::resource('microCompetences', MicroCompetenceController::class)
            ->parameters(['microCompetences' => 'microCompetence']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('microCompetences/import', [MicroCompetenceController::class, 'import'])->name('microCompetences.import');
            Route::get('microCompetences/export/{format}', [MicroCompetenceController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('microCompetences.export');

        });

        Route::post('microCompetences/data-calcul', [MicroCompetenceController::class, 'dataCalcul'])->name('microCompetences.dataCalcul');
        Route::post('microCompetences/update-attributes', [MicroCompetenceController::class, 'updateAttributes'])->name('microCompetences.updateAttributes');
        Route::get('microCompetences/startFormation/{id}', [MicroCompetenceController::class, 'startFormation'])->name('microCompetences.startFormation');
    
    

    });
});
