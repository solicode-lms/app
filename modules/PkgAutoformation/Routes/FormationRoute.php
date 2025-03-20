<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgAutoformation\Controllers\FormationController;

// routes for formation management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgAutoformation')->group(function () {

        Route::get('formations/getData', [FormationController::class, 'getData'])->name('formations.getData');
        Route::resource('formations', FormationController::class)
            ->parameters(['formations' => 'formation']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('formations/import', [FormationController::class, 'import'])->name('formations.import');
            Route::get('formations/export/{format}', [FormationController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('formations.export');

        });

        Route::post('formations/data-calcul', [FormationController::class, 'dataCalcul'])->name('formations.dataCalcul');

    });
});
