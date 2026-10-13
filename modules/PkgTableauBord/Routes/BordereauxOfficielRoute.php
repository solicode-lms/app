<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgTableauBord\Controllers\BordereauxOfficielController;

// routes for bordereauxOfficiel management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgTableauBord')->group(function () {

        // Edition inline
        Route::get('bordereauxOfficiels/{id}/field/{field}/meta', [BordereauxOfficielController::class, 'fieldMeta'])
            ->name('bordereauxOfficiels.field.meta');
        Route::patch('bordereauxOfficiels/{id}/inline', [BordereauxOfficielController::class, 'patchInline'])
            ->name('bordereauxOfficiels.patchInline');

        Route::get('bordereauxOfficiels/getData', [BordereauxOfficielController::class, 'getData'])->name('bordereauxOfficiels.getData');
        // ✅ Route JSON
        Route::get('bordereauxOfficiels/json/{id}', [BordereauxOfficielController::class, 'getBordereauxOfficiel'])
            ->name('bordereauxOfficiels.getById');
        // bulk - edit and delete
        Route::post('bordereauxOfficiels/bulk-delete', [BordereauxOfficielController::class, 'bulkDelete'])
        ->name('bordereauxOfficiels.bulkDelete');
        Route::get('bordereauxOfficiels/bulk-edit', [BordereauxOfficielController::class, 'bulkEditForm'])
        ->name('bordereauxOfficiels.bulkEdit');
        Route::post('bordereauxOfficiels/bulk-update', [BordereauxOfficielController::class, 'bulkUpdate'])
        ->name('bordereauxOfficiels.bulkUpdate');

        Route::resource('bordereauxOfficiels', BordereauxOfficielController::class)
            ->parameters(['bordereauxOfficiels' => 'bordereauxOfficiel']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('bordereauxOfficiels/import', [BordereauxOfficielController::class, 'import'])->name('bordereauxOfficiels.import');
            Route::get('bordereauxOfficiels/export/{format}', [BordereauxOfficielController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('bordereauxOfficiels.export');

        });

        Route::post('bordereauxOfficiels/data-calcul', [BordereauxOfficielController::class, 'dataCalcul'])->name('bordereauxOfficiels.dataCalcul');
        Route::post('bordereauxOfficiels/update-attributes', [BordereauxOfficielController::class, 'updateAttributes'])->name('bordereauxOfficiels.updateAttributes');

    

    });
});
