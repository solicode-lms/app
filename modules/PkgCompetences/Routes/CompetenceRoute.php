<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCompetences\Controllers\CompetenceController;

// routes for competence management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCompetences')->group(function () {
        Route::get('competences/getData', [CompetenceController::class, 'getData'])->name('competences.getData');
        // ✅ Route JSON
        Route::get('competences/json/{id}', [CompetenceController::class, 'getCompetence'])
            ->name('competences.getById');
        // bulk - edit and delete
        Route::post('competences/bulk-delete', [CompetenceController::class, 'bulkDelete'])
        ->name('competences.bulkDelete');
        Route::get('competences/bulk-edit', [CompetenceController::class, 'bulkEditForm'])
        ->name('competences.bulkEdit');
        Route::post('competences/bulk-update', [CompetenceController::class, 'bulkUpdate'])
        ->name('competences.bulkUpdate');

        Route::resource('competences', CompetenceController::class)
            ->parameters(['competences' => 'competence']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('competences/import', [CompetenceController::class, 'import'])->name('competences.import');
            Route::get('competences/export/{format}', [CompetenceController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('competences.export');

        });

        Route::post('competences/data-calcul', [CompetenceController::class, 'dataCalcul'])->name('competences.dataCalcul');
        Route::post('competences/update-attributes', [CompetenceController::class, 'updateAttributes'])->name('competences.updateAttributes');

    

    });
});
