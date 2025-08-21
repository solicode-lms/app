<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCompetences\Controllers\ChapitreController;

// routes for chapitre management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCompetences')->group(function () {

        // Edition inline
        Route::get('chapitres/{id}/field/{field}/meta', [ChapitreController::class, 'fieldMeta'])
            ->name('chapitres.field.meta');
        Route::patch('chapitres/{id}/inline', [ChapitreController::class, 'patchInline'])
            ->name('chapitres.patchInline');

        Route::get('chapitres/getData', [ChapitreController::class, 'getData'])->name('chapitres.getData');
        // ✅ Route JSON
        Route::get('chapitres/json/{id}', [ChapitreController::class, 'getChapitre'])
            ->name('chapitres.getById');
        // bulk - edit and delete
        Route::post('chapitres/bulk-delete', [ChapitreController::class, 'bulkDelete'])
        ->name('chapitres.bulkDelete');
        Route::get('chapitres/bulk-edit', [ChapitreController::class, 'bulkEditForm'])
        ->name('chapitres.bulkEdit');
        Route::post('chapitres/bulk-update', [ChapitreController::class, 'bulkUpdate'])
        ->name('chapitres.bulkUpdate');

        Route::resource('chapitres', ChapitreController::class)
            ->parameters(['chapitres' => 'chapitre']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('chapitres/import', [ChapitreController::class, 'import'])->name('chapitres.import');
            Route::get('chapitres/export/{format}', [ChapitreController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('chapitres.export');

        });

        Route::post('chapitres/data-calcul', [ChapitreController::class, 'dataCalcul'])->name('chapitres.dataCalcul');
        Route::post('chapitres/update-attributes', [ChapitreController::class, 'updateAttributes'])->name('chapitres.updateAttributes');

    

    });
});
