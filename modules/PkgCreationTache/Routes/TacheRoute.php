<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCreationTache\Controllers\TacheController;

// routes for tache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCreationTache')->group(function () {

        // Edition inline
        Route::get('taches/{id}/field/{field}/meta', [TacheController::class, 'fieldMeta'])
            ->name('taches.field.meta');
        Route::patch('taches/{id}/inline', [TacheController::class, 'patchInline'])
            ->name('taches.patchInline');

        Route::get('taches/getData', [TacheController::class, 'getData'])->name('taches.getData');
        // ✅ Route JSON
        Route::get('taches/json/{id}', [TacheController::class, 'getTache'])
            ->name('taches.getById');
        // bulk - edit and delete
        Route::post('taches/bulk-delete', [TacheController::class, 'bulkDelete'])
        ->name('taches.bulkDelete');
        Route::get('taches/bulk-edit', [TacheController::class, 'bulkEditForm'])
        ->name('taches.bulkEdit');
        Route::post('taches/bulk-update', [TacheController::class, 'bulkUpdate'])
        ->name('taches.bulkUpdate');

        Route::resource('taches', TacheController::class)
            ->parameters(['taches' => 'tache']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('taches/import', [TacheController::class, 'import'])->name('taches.import');
            Route::get('taches/export/{format}', [TacheController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('taches.export');

        });

        Route::post('taches/data-calcul', [TacheController::class, 'dataCalcul'])->name('taches.dataCalcul');
        Route::post('taches/update-attributes', [TacheController::class, 'updateAttributes'])->name('taches.updateAttributes');

    

    });
});
