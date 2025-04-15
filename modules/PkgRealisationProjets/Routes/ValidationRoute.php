<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgRealisationProjets\Controllers\ValidationController;

// routes for validation management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgRealisationProjets')->group(function () {

        Route::get('validations/getData', [ValidationController::class, 'getData'])->name('validations.getData');
        // bulk - edit and delete
        Route::post('validations/bulk-delete', [ValidationController::class, 'bulkDelete'])
        ->name('validations.bulkDelete');
        Route::get('validations/bulk-edit', [ValidationController::class, 'bulkEditForm'])
        ->name('validations.bulkEdit');
        Route::post('validations/bulk-update', [ValidationController::class, 'bulkUpdate'])
        ->name('validations.bulkUpdate');

        Route::resource('validations', ValidationController::class)
            ->parameters(['validations' => 'validation']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('validations/import', [ValidationController::class, 'import'])->name('validations.import');
            Route::get('validations/export/{format}', [ValidationController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('validations.export');

        });

        Route::post('validations/data-calcul', [ValidationController::class, 'dataCalcul'])->name('validations.dataCalcul');
        Route::post('validations/update-attributes', [ValidationController::class, 'updateAttributes'])->name('validations.updateAttributes');

    

    });
});
