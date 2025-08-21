<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgApprenants\Controllers\ApprenantKonosyController;

// routes for apprenantKonosy management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgApprenants')->group(function () {

        // Edition inline
        Route::get('apprenantKonosies/{id}/field/{field}/meta', [ApprenantKonosyController::class, 'fieldMeta'])
            ->name('apprenantKonosies.field.meta');
        Route::patch('apprenantKonosies/{id}/inline', [ApprenantKonosyController::class, 'patchInline'])
            ->name('apprenantKonosies.patchInline');

        Route::get('apprenantKonosies/getData', [ApprenantKonosyController::class, 'getData'])->name('apprenantKonosies.getData');
        // ✅ Route JSON
        Route::get('apprenantKonosies/json/{id}', [ApprenantKonosyController::class, 'getApprenantKonosy'])
            ->name('apprenantKonosies.getById');
        // bulk - edit and delete
        Route::post('apprenantKonosies/bulk-delete', [ApprenantKonosyController::class, 'bulkDelete'])
        ->name('apprenantKonosies.bulkDelete');
        Route::get('apprenantKonosies/bulk-edit', [ApprenantKonosyController::class, 'bulkEditForm'])
        ->name('apprenantKonosies.bulkEdit');
        Route::post('apprenantKonosies/bulk-update', [ApprenantKonosyController::class, 'bulkUpdate'])
        ->name('apprenantKonosies.bulkUpdate');

        Route::resource('apprenantKonosies', ApprenantKonosyController::class)
            ->parameters(['apprenantKonosies' => 'apprenantKonosy']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('apprenantKonosies/import', [ApprenantKonosyController::class, 'import'])->name('apprenantKonosies.import');
            Route::get('apprenantKonosies/export/{format}', [ApprenantKonosyController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('apprenantKonosies.export');

        });

        Route::post('apprenantKonosies/data-calcul', [ApprenantKonosyController::class, 'dataCalcul'])->name('apprenantKonosies.dataCalcul');
        Route::post('apprenantKonosies/update-attributes', [ApprenantKonosyController::class, 'updateAttributes'])->name('apprenantKonosies.updateAttributes');

    

    });
});
