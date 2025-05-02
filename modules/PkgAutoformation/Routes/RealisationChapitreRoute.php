<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgAutoformation\Controllers\RealisationChapitreController;

// routes for realisationChapitre management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgAutoformation')->group(function () {
        Route::get('realisationChapitres/getData', [RealisationChapitreController::class, 'getData'])->name('realisationChapitres.getData');
        // bulk - edit and delete
        Route::post('realisationChapitres/bulk-delete', [RealisationChapitreController::class, 'bulkDelete'])
        ->name('realisationChapitres.bulkDelete');
        Route::get('realisationChapitres/bulk-edit', [RealisationChapitreController::class, 'bulkEditForm'])
        ->name('realisationChapitres.bulkEdit');
        Route::post('realisationChapitres/bulk-update', [RealisationChapitreController::class, 'bulkUpdate'])
        ->name('realisationChapitres.bulkUpdate');

        Route::resource('realisationChapitres', RealisationChapitreController::class)
            ->parameters(['realisationChapitres' => 'realisationChapitre']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('realisationChapitres/import', [RealisationChapitreController::class, 'import'])->name('realisationChapitres.import');
            Route::get('realisationChapitres/export/{format}', [RealisationChapitreController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('realisationChapitres.export');

        });

        Route::post('realisationChapitres/data-calcul', [RealisationChapitreController::class, 'dataCalcul'])->name('realisationChapitres.dataCalcul');
        Route::post('realisationChapitres/update-attributes', [RealisationChapitreController::class, 'updateAttributes'])->name('realisationChapitres.updateAttributes');

    

    });
});
