<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgFormation\Controllers\SpecialiteController;

// routes for specialite management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgFormation')->group(function () {

        // Edition inline
        Route::get('specialites/{id}/field/{field}/meta', [SpecialiteController::class, 'fieldMeta'])
            ->name('specialites.field.meta');
        Route::patch('specialites/{id}/inline', [SpecialiteController::class, 'patchInline'])
            ->name('specialites.patchInline');

        Route::get('specialites/getData', [SpecialiteController::class, 'getData'])->name('specialites.getData');
        // ✅ Route JSON
        Route::get('specialites/json/{id}', [SpecialiteController::class, 'getSpecialite'])
            ->name('specialites.getById');
        // bulk - edit and delete
        Route::post('specialites/bulk-delete', [SpecialiteController::class, 'bulkDelete'])
        ->name('specialites.bulkDelete');
        Route::get('specialites/bulk-edit', [SpecialiteController::class, 'bulkEditForm'])
        ->name('specialites.bulkEdit');
        Route::post('specialites/bulk-update', [SpecialiteController::class, 'bulkUpdate'])
        ->name('specialites.bulkUpdate');

        Route::resource('specialites', SpecialiteController::class)
            ->parameters(['specialites' => 'specialite']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('specialites/import', [SpecialiteController::class, 'import'])->name('specialites.import');
            Route::get('specialites/export/{format}', [SpecialiteController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('specialites.export');

        });

        Route::post('specialites/data-calcul', [SpecialiteController::class, 'dataCalcul'])->name('specialites.dataCalcul');
        Route::post('specialites/update-attributes', [SpecialiteController::class, 'updateAttributes'])->name('specialites.updateAttributes');

    

    });
});
