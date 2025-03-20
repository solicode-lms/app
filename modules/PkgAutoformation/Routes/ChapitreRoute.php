<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgAutoformation\Controllers\ChapitreController;

// routes for chapitre management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgAutoformation')->group(function () {

        Route::get('chapitres/getData', [ChapitreController::class, 'getData'])->name('chapitres.getData');
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

    });
});
