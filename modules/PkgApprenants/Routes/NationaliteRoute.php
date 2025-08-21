<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgApprenants\Controllers\NationaliteController;

// routes for nationalite management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgApprenants')->group(function () {

        // Edition inline
        Route::get('nationalites/{id}/field/{field}/meta', [NationaliteController::class, 'fieldMeta'])
            ->name('nationalites.field.meta');
        Route::patch('nationalites/{id}/inline', [NationaliteController::class, 'patchInline'])
            ->name('nationalites.patchInline');

        Route::get('nationalites/getData', [NationaliteController::class, 'getData'])->name('nationalites.getData');
        // ✅ Route JSON
        Route::get('nationalites/json/{id}', [NationaliteController::class, 'getNationalite'])
            ->name('nationalites.getById');
        // bulk - edit and delete
        Route::post('nationalites/bulk-delete', [NationaliteController::class, 'bulkDelete'])
        ->name('nationalites.bulkDelete');
        Route::get('nationalites/bulk-edit', [NationaliteController::class, 'bulkEditForm'])
        ->name('nationalites.bulkEdit');
        Route::post('nationalites/bulk-update', [NationaliteController::class, 'bulkUpdate'])
        ->name('nationalites.bulkUpdate');

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
        Route::post('nationalites/update-attributes', [NationaliteController::class, 'updateAttributes'])->name('nationalites.updateAttributes');

    

    });
});
