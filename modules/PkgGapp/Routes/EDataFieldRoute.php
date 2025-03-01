<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGapp\Controllers\EDataFieldController;

// routes for eDataField management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGapp')->group(function () {

        Route::get('eDataFields/getEDataFields', [EDataFieldController::class, 'getEDataFields'])->name('eDataFields.all');
        Route::resource('eDataFields', EDataFieldController::class)
            ->parameters(['eDataFields' => 'eDataField']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('eDataFields/import', [EDataFieldController::class, 'import'])->name('eDataFields.import');
            Route::get('eDataFields/export/{format}', [EDataFieldController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('eDataFields.export');

        });

        Route::post('eDataFields/data-calcul', [EDataFieldController::class, 'dataCalcul'])->name('eDataFields.dataCalcul');

    });
});
