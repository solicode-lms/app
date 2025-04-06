<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgFormation\Controllers\FiliereController;

// routes for filiere management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgFormation')->group(function () {

        Route::get('filieres/getData', [FiliereController::class, 'getData'])->name('filieres.getData');
        Route::resource('filieres', FiliereController::class)
            ->parameters(['filieres' => 'filiere']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('filieres/import', [FiliereController::class, 'import'])->name('filieres.import');
            Route::get('filieres/export/{format}', [FiliereController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('filieres.export');

        });

        Route::post('filieres/data-calcul', [FiliereController::class, 'dataCalcul'])->name('filieres.dataCalcul');
        Route::post('filieres/update-attributes', [FiliereController::class, 'updateAttributes'])->name('filieres.updateAttributes');

    

    });
});
