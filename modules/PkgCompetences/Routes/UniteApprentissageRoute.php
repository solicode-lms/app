<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCompetences\Controllers\UniteApprentissageController;

// routes for uniteApprentissage management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCompetences')->group(function () {
        Route::get('uniteApprentissages/getData', [UniteApprentissageController::class, 'getData'])->name('uniteApprentissages.getData');
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
