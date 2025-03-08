<?php
// TODO : add methode to Controller 



use Illuminate\Support\Facades\Route;
use Modules\PkgGestionTaches\Controllers\TacheController;

// routes for tache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGestionTaches')->group(function () {

        Route::get('taches/getData', [TacheController::class, 'getData'])->name('taches.getData');

        Route::resource('taches', TacheController::class)
            ->parameters(['taches' => 'tache']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('taches/import', [TacheController::class, 'import'])->name('taches.import');
            Route::get('taches/export/{format}', [TacheController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('taches.export');

        });

        Route::post('taches/data-calcul', [TacheController::class, 'dataCalcul'])->name('taches.dataCalcul');

    });
});
