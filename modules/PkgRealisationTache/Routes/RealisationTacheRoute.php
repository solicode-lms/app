<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgRealisationTache\Controllers\RealisationTacheController;

// routes for realisationTache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgRealisationTache')->group(function () {
        
        
         // ====================================================
        // ✅ Nouvelles routes inline edit avec le même controller
        // ====================================================
        Route::get('realisationTaches/{id}/field/{field}/meta', [RealisationTacheController::class, 'fieldMeta'])
            ->name('realisationTaches.field.meta');

        Route::patch('realisationTaches/{id}/inline', [RealisationTacheController::class, 'patchInline'])
            ->name('realisationTaches.patchInline');

        
        
        Route::get('realisationTaches/getData', [RealisationTacheController::class, 'getData'])->name('realisationTaches.getData');
        // ✅ Route JSON
        Route::get('realisationTaches/json/{id}', [RealisationTacheController::class, 'getRealisationTache'])
            ->name('realisationTaches.getById');
        // bulk - edit and delete
        Route::post('realisationTaches/bulk-delete', [RealisationTacheController::class, 'bulkDelete'])
        ->name('realisationTaches.bulkDelete');
        Route::get('realisationTaches/bulk-edit', [RealisationTacheController::class, 'bulkEditForm'])
        ->name('realisationTaches.bulkEdit');
        Route::post('realisationTaches/bulk-update', [RealisationTacheController::class, 'bulkUpdate'])
        ->name('realisationTaches.bulkUpdate');

        Route::resource('realisationTaches', RealisationTacheController::class)
            ->parameters(['realisationTaches' => 'realisationTache']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('realisationTaches/import', [RealisationTacheController::class, 'import'])->name('realisationTaches.import');
            Route::get('realisationTaches/export/{format}', [RealisationTacheController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('realisationTaches.export');

        });

        Route::post('realisationTaches/data-calcul', [RealisationTacheController::class, 'dataCalcul'])->name('realisationTaches.dataCalcul');
        Route::post('realisationTaches/update-attributes', [RealisationTacheController::class, 'updateAttributes'])->name('realisationTaches.updateAttributes');

    
       


    });
});
