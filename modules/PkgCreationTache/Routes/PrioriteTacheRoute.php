<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCreationTache\Controllers\PrioriteTacheController;

// routes for prioriteTache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCreationTache')->group(function () {

        // Edition inline
        Route::get('prioriteTaches/{id}/field/{field}/meta', [PrioriteTacheController::class, 'fieldMeta'])
            ->name('prioriteTaches.field.meta');
        Route::patch('prioriteTaches/{id}/inline', [PrioriteTacheController::class, 'patchInline'])
            ->name('prioriteTaches.patchInline');

        Route::get('prioriteTaches/getData', [PrioriteTacheController::class, 'getData'])->name('prioriteTaches.getData');
        // ✅ Route JSON
        Route::get('prioriteTaches/json/{id}', [PrioriteTacheController::class, 'getPrioriteTache'])
            ->name('prioriteTaches.getById');
        // bulk - edit and delete
        Route::post('prioriteTaches/bulk-delete', [PrioriteTacheController::class, 'bulkDelete'])
        ->name('prioriteTaches.bulkDelete');
        Route::get('prioriteTaches/bulk-edit', [PrioriteTacheController::class, 'bulkEditForm'])
        ->name('prioriteTaches.bulkEdit');
        Route::post('prioriteTaches/bulk-update', [PrioriteTacheController::class, 'bulkUpdate'])
        ->name('prioriteTaches.bulkUpdate');

        Route::resource('prioriteTaches', PrioriteTacheController::class)
            ->parameters(['prioriteTaches' => 'prioriteTache']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('prioriteTaches/import', [PrioriteTacheController::class, 'import'])->name('prioriteTaches.import');
            Route::get('prioriteTaches/export/{format}', [PrioriteTacheController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('prioriteTaches.export');

        });

        Route::post('prioriteTaches/data-calcul', [PrioriteTacheController::class, 'dataCalcul'])->name('prioriteTaches.dataCalcul');
        Route::post('prioriteTaches/update-attributes', [PrioriteTacheController::class, 'updateAttributes'])->name('prioriteTaches.updateAttributes');

    

    });
});
