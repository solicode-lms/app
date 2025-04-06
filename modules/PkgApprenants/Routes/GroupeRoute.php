<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgApprenants\Controllers\GroupeController;

// routes for groupe management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgApprenants')->group(function () {

        Route::get('groupes/getData', [GroupeController::class, 'getData'])->name('groupes.getData');
        Route::resource('groupes', GroupeController::class)
            ->parameters(['groupes' => 'groupe']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('groupes/import', [GroupeController::class, 'import'])->name('groupes.import');
            Route::get('groupes/export/{format}', [GroupeController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('groupes.export');

        });

        Route::post('groupes/data-calcul', [GroupeController::class, 'dataCalcul'])->name('groupes.dataCalcul');
        Route::post('groupes/update-attributes', [GroupeController::class, 'updateAttributes'])->name('groupes.updateAttributes');

    

    });
});
