<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgRealisationTache\Controllers\RevisionTacheController;

// routes for revisionTache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgRealisationTache')->group(function () {

        // Edition inline
        Route::get('revisionTaches/{id}/field/{field}/meta', [RevisionTacheController::class, 'fieldMeta'])
            ->name('revisionTaches.field.meta');
        Route::patch('revisionTaches/{id}/inline', [RevisionTacheController::class, 'patchInline'])
            ->name('revisionTaches.patchInline');

        Route::get('revisionTaches/getData', [RevisionTacheController::class, 'getData'])->name('revisionTaches.getData');
        // ✅ Route JSON
        Route::get('revisionTaches/json/{id}', [RevisionTacheController::class, 'getRevisionTache'])
            ->name('revisionTaches.getById');
        // bulk - edit and delete
        Route::post('revisionTaches/bulk-delete', [RevisionTacheController::class, 'bulkDelete'])
        ->name('revisionTaches.bulkDelete');
        Route::get('revisionTaches/bulk-edit', [RevisionTacheController::class, 'bulkEditForm'])
        ->name('revisionTaches.bulkEdit');
        Route::post('revisionTaches/bulk-update', [RevisionTacheController::class, 'bulkUpdate'])
        ->name('revisionTaches.bulkUpdate');

        Route::resource('revisionTaches', RevisionTacheController::class)
            ->parameters(['revisionTaches' => 'revisionTache']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('revisionTaches/import', [RevisionTacheController::class, 'import'])->name('revisionTaches.import');
            Route::get('revisionTaches/export/{format}', [RevisionTacheController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('revisionTaches.export');

        });

        Route::post('revisionTaches/data-calcul', [RevisionTacheController::class, 'dataCalcul'])->name('revisionTaches.dataCalcul');
        Route::post('revisionTaches/update-attributes', [RevisionTacheController::class, 'updateAttributes'])->name('revisionTaches.updateAttributes');

    

    });
});
