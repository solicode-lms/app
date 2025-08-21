<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCompetences\Controllers\UniteApprentissageController;

// routes for uniteApprentissage management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCompetences')->group(function () {

        // Edition inline
        Route::get('uniteApprentissages/{id}/field/{field}/meta', [UniteApprentissageController::class, 'fieldMeta'])
            ->name('uniteApprentissages.field.meta');
        Route::patch('uniteApprentissages/{id}/inline', [UniteApprentissageController::class, 'patchInline'])
            ->name('uniteApprentissages.patchInline');

        Route::get('uniteApprentissages/getData', [UniteApprentissageController::class, 'getData'])->name('uniteApprentissages.getData');
        // ✅ Route JSON
        Route::get('uniteApprentissages/json/{id}', [UniteApprentissageController::class, 'getUniteApprentissage'])
            ->name('uniteApprentissages.getById');
        // bulk - edit and delete
        Route::post('uniteApprentissages/bulk-delete', [UniteApprentissageController::class, 'bulkDelete'])
        ->name('uniteApprentissages.bulkDelete');
        Route::get('uniteApprentissages/bulk-edit', [UniteApprentissageController::class, 'bulkEditForm'])
        ->name('uniteApprentissages.bulkEdit');
        Route::post('uniteApprentissages/bulk-update', [UniteApprentissageController::class, 'bulkUpdate'])
        ->name('uniteApprentissages.bulkUpdate');

        Route::resource('uniteApprentissages', UniteApprentissageController::class)
            ->parameters(['uniteApprentissages' => 'uniteApprentissage']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('uniteApprentissages/import', [UniteApprentissageController::class, 'import'])->name('uniteApprentissages.import');
            Route::get('uniteApprentissages/export/{format}', [UniteApprentissageController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('uniteApprentissages.export');

        });

        Route::post('uniteApprentissages/data-calcul', [UniteApprentissageController::class, 'dataCalcul'])->name('uniteApprentissages.dataCalcul');
        Route::post('uniteApprentissages/update-attributes', [UniteApprentissageController::class, 'updateAttributes'])->name('uniteApprentissages.updateAttributes');

    

    });
});
