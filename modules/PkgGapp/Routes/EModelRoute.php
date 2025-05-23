<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGapp\Controllers\EModelController;

// routes for eModel management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGapp')->group(function () {
        Route::get('eModels/getData', [EModelController::class, 'getData'])->name('eModels.getData');
        // bulk - edit and delete
        Route::post('eModels/bulk-delete', [EModelController::class, 'bulkDelete'])
        ->name('eModels.bulkDelete');
        Route::get('eModels/bulk-edit', [EModelController::class, 'bulkEditForm'])
        ->name('eModels.bulkEdit');
        Route::post('eModels/bulk-update', [EModelController::class, 'bulkUpdate'])
        ->name('eModels.bulkUpdate');

        Route::resource('eModels', EModelController::class)
            ->parameters(['eModels' => 'eModel']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('eModels/import', [EModelController::class, 'import'])->name('eModels.import');
            Route::get('eModels/export/{format}', [EModelController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('eModels.export');

        });

        Route::post('eModels/data-calcul', [EModelController::class, 'dataCalcul'])->name('eModels.dataCalcul');
        Route::post('eModels/update-attributes', [EModelController::class, 'updateAttributes'])->name('eModels.updateAttributes');

    

    });
});
