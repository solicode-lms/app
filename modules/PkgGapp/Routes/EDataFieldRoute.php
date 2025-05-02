<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGapp\Controllers\EDataFieldController;

// routes for eDataField management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGapp')->group(function () {
        Route::get('eDataFields/getData', [EDataFieldController::class, 'getData'])->name('eDataFields.getData');
        // bulk - edit and delete
        Route::post('eDataFields/bulk-delete', [EDataFieldController::class, 'bulkDelete'])
        ->name('eDataFields.bulkDelete');
        Route::get('eDataFields/bulk-edit', [EDataFieldController::class, 'bulkEditForm'])
        ->name('eDataFields.bulkEdit');
        Route::post('eDataFields/bulk-update', [EDataFieldController::class, 'bulkUpdate'])
        ->name('eDataFields.bulkUpdate');

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
        Route::post('eDataFields/update-attributes', [EDataFieldController::class, 'updateAttributes'])->name('eDataFields.updateAttributes');

    

    });
});
