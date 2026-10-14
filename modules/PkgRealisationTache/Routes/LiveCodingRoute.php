<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgRealisationTache\Controllers\LiveCodingController;

// routes for liveCoding management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgRealisationTache')->group(function () {

        // Edition inline
        Route::get('liveCodings/{id}/field/{field}/meta', [LiveCodingController::class, 'fieldMeta'])
            ->name('liveCodings.field.meta');
        Route::patch('liveCodings/{id}/inline', [LiveCodingController::class, 'patchInline'])
            ->name('liveCodings.patchInline');

        Route::get('liveCodings/getData', [LiveCodingController::class, 'getData'])->name('liveCodings.getData');
        // ✅ Route JSON
        Route::get('liveCodings/json/{id}', [LiveCodingController::class, 'getLiveCoding'])
            ->name('liveCodings.getById');
        // bulk - edit and delete
        Route::post('liveCodings/bulk-delete', [LiveCodingController::class, 'bulkDelete'])
        ->name('liveCodings.bulkDelete');
        Route::get('liveCodings/bulk-edit', [LiveCodingController::class, 'bulkEditForm'])
        ->name('liveCodings.bulkEdit');
        Route::post('liveCodings/bulk-update', [LiveCodingController::class, 'bulkUpdate'])
        ->name('liveCodings.bulkUpdate');

        Route::resource('liveCodings', LiveCodingController::class)
            ->parameters(['liveCodings' => 'liveCoding']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('liveCodings/import', [LiveCodingController::class, 'import'])->name('liveCodings.import');
            Route::get('liveCodings/export/{format}', [LiveCodingController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('liveCodings.export');

        });

        Route::post('liveCodings/data-calcul', [LiveCodingController::class, 'dataCalcul'])->name('liveCodings.dataCalcul');
        Route::post('liveCodings/update-attributes', [LiveCodingController::class, 'updateAttributes'])->name('liveCodings.updateAttributes');

    

    });
});
