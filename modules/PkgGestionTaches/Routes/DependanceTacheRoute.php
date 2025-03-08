<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGestionTaches\Controllers\DependanceTacheController;

// routes for dependanceTache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGestionTaches')->group(function () {

        Route::get('dependanceTaches/getData', [DependanceTacheController::class, 'getData'])->name('dependanceTaches.getData');
        Route::resource('dependanceTaches', DependanceTacheController::class)
            ->parameters(['dependanceTaches' => 'dependanceTache']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('dependanceTaches/import', [DependanceTacheController::class, 'import'])->name('dependanceTaches.import');
            Route::get('dependanceTaches/export/{format}', [DependanceTacheController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('dependanceTaches.export');

        });

        Route::post('dependanceTaches/data-calcul', [DependanceTacheController::class, 'dataCalcul'])->name('dependanceTaches.dataCalcul');

    });
});
