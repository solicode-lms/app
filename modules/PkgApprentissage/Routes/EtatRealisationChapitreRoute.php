<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgApprentissage\Controllers\EtatRealisationChapitreController;

// routes for etatRealisationChapitre management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgApprentissage')->group(function () {
        Route::get('etatRealisationChapitres/getData', [EtatRealisationChapitreController::class, 'getData'])->name('etatRealisationChapitres.getData');
        // bulk - edit and delete
        Route::post('etatRealisationChapitres/bulk-delete', [EtatRealisationChapitreController::class, 'bulkDelete'])
        ->name('etatRealisationChapitres.bulkDelete');
        Route::get('etatRealisationChapitres/bulk-edit', [EtatRealisationChapitreController::class, 'bulkEditForm'])
        ->name('etatRealisationChapitres.bulkEdit');
        Route::post('etatRealisationChapitres/bulk-update', [EtatRealisationChapitreController::class, 'bulkUpdate'])
        ->name('etatRealisationChapitres.bulkUpdate');

        Route::resource('etatRealisationChapitres', EtatRealisationChapitreController::class)
            ->parameters(['etatRealisationChapitres' => 'etatRealisationChapitre']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('etatRealisationChapitres/import', [EtatRealisationChapitreController::class, 'import'])->name('etatRealisationChapitres.import');
            Route::get('etatRealisationChapitres/export/{format}', [EtatRealisationChapitreController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('etatRealisationChapitres.export');

        });

        Route::post('etatRealisationChapitres/data-calcul', [EtatRealisationChapitreController::class, 'dataCalcul'])->name('etatRealisationChapitres.dataCalcul');
        Route::post('etatRealisationChapitres/update-attributes', [EtatRealisationChapitreController::class, 'updateAttributes'])->name('etatRealisationChapitres.updateAttributes');

    

    });
});
