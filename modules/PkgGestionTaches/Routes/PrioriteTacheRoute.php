<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGestionTaches\Controllers\PrioriteTacheController;

// routes for prioriteTache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGestionTaches')->group(function () {

        Route::get('prioriteTaches/getPrioriteTaches', [PrioriteTacheController::class, 'getPrioriteTaches'])->name('prioriteTaches.all');
        Route::resource('prioriteTaches', PrioriteTacheController::class)
            ->parameters(['prioriteTaches' => 'prioriteTache']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('prioriteTaches/import', [PrioriteTacheController::class, 'import'])->name('prioriteTaches.import');
            Route::get('prioriteTaches/export/{format}', [PrioriteTacheController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('prioriteTaches.export');

        });

        Route::post('prioriteTaches/data-calcul', [PrioriteTacheController::class, 'dataCalcul'])->name('prioriteTaches.dataCalcul');

    });
});
