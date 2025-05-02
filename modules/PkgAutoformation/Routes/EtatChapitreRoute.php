<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgAutoformation\Controllers\EtatChapitreController;

// routes for etatChapitre management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgAutoformation')->group(function () {
        Route::get('etatChapitres/getData', [EtatChapitreController::class, 'getData'])->name('etatChapitres.getData');
        // bulk - edit and delete
        Route::post('etatChapitres/bulk-delete', [EtatChapitreController::class, 'bulkDelete'])
        ->name('etatChapitres.bulkDelete');
        Route::get('etatChapitres/bulk-edit', [EtatChapitreController::class, 'bulkEditForm'])
        ->name('etatChapitres.bulkEdit');
        Route::post('etatChapitres/bulk-update', [EtatChapitreController::class, 'bulkUpdate'])
        ->name('etatChapitres.bulkUpdate');

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
