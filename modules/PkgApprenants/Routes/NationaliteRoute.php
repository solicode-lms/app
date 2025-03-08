<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgApprenants\Controllers\NationaliteController;

// routes for nationalite management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgApprenants')->group(function () {

        Route::get('nationalites/getData', [NationaliteController::class, 'getData'])->name('nationalites.getData');
        Route::resource('nationalites', NationaliteController::class)
            ->parameters(['nationalites' => 'nationalite']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('nationalites/import', [NationaliteController::class, 'import'])->name('nationalites.import');
            Route::get('nationalites/export/{format}', [NationaliteController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('nationalites.export');

        });

        Route::post('nationalites/data-calcul', [NationaliteController::class, 'dataCalcul'])->name('nationalites.dataCalcul');

    });
});
