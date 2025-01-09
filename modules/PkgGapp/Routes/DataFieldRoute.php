<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGapp\Controllers\DataFieldController;

// routes for dataField management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGapp')->group(function () {

        Route::get('dataFields/getDataFields', [DataFieldController::class, 'getDataFields'])->name('dataFields.all');
        Route::resource('dataFields', DataFieldController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('dataFields/export', [DataFieldController::class, 'export'])->name('dataFields.export');
            Route::post('dataFields/import', [DataFieldController::class, 'import'])->name('dataFields.import');
        });
    });
});
