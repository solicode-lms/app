<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgAutoformation\Controllers\EtatChapitreController;

// routes for etatChapitre management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgAutoformation')->group(function () {

        Route::get('etatChapitres/getData', [EtatChapitreController::class, 'getData'])->name('etatChapitres.getData');
        Route::resource('etatChapitres', EtatChapitreController::class)
            ->parameters(['etatChapitres' => 'etatChapitre']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('etatChapitres/import', [EtatChapitreController::class, 'import'])->name('etatChapitres.import');
            Route::get('etatChapitres/export/{format}', [EtatChapitreController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('etatChapitres.export');

        });

        Route::post('etatChapitres/data-calcul', [EtatChapitreController::class, 'dataCalcul'])->name('etatChapitres.dataCalcul');
        Route::post('etatChapitres/update-attributes', [EtatChapitreController::class, 'updateAttributes'])->name('etatChapitres.updateAttributes');

    

    });
});
