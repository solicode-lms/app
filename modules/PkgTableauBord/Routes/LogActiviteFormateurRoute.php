<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgTableauBord\Controllers\LogActiviteFormateurController;

// routes for logActiviteFormateur management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgTableauBord')->group(function () {

        // Edition inline
        Route::get('logActiviteFormateurs/{id}/field/{field}/meta', [LogActiviteFormateurController::class, 'fieldMeta'])
            ->name('logActiviteFormateurs.field.meta');
        Route::patch('logActiviteFormateurs/{id}/inline', [LogActiviteFormateurController::class, 'patchInline'])
            ->name('logActiviteFormateurs.patchInline');

        Route::get('logActiviteFormateurs/getData', [LogActiviteFormateurController::class, 'getData'])->name('logActiviteFormateurs.getData');
        // ✅ Route JSON
        Route::get('logActiviteFormateurs/json/{id}', [LogActiviteFormateurController::class, 'getLogActiviteFormateur'])
            ->name('logActiviteFormateurs.getById');
        // bulk - edit and delete
        Route::post('logActiviteFormateurs/bulk-delete', [LogActiviteFormateurController::class, 'bulkDelete'])
        ->name('logActiviteFormateurs.bulkDelete');
        Route::get('logActiviteFormateurs/bulk-edit', [LogActiviteFormateurController::class, 'bulkEditForm'])
        ->name('logActiviteFormateurs.bulkEdit');
        Route::post('logActiviteFormateurs/bulk-update', [LogActiviteFormateurController::class, 'bulkUpdate'])
        ->name('logActiviteFormateurs.bulkUpdate');

        Route::resource('logActiviteFormateurs', LogActiviteFormateurController::class)
            ->parameters(['logActiviteFormateurs' => 'logActiviteFormateur']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('logActiviteFormateurs/import', [LogActiviteFormateurController::class, 'import'])->name('logActiviteFormateurs.import');
            Route::get('logActiviteFormateurs/export/{format}', [LogActiviteFormateurController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('logActiviteFormateurs.export');

        });

        Route::post('logActiviteFormateurs/data-calcul', [LogActiviteFormateurController::class, 'dataCalcul'])->name('logActiviteFormateurs.dataCalcul');
        Route::post('logActiviteFormateurs/update-attributes', [LogActiviteFormateurController::class, 'updateAttributes'])->name('logActiviteFormateurs.updateAttributes');

    

    });
});
