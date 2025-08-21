<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCreationProjet\Controllers\ResourceController;

// routes for resource management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCreationProjet')->group(function () {

        // Edition inline
        Route::get('resources/{id}/field/{field}/meta', [ResourceController::class, 'fieldMeta'])
            ->name('resources.field.meta');
        Route::patch('resources/{id}/inline', [ResourceController::class, 'patchInline'])
            ->name('resources.patchInline');

        Route::get('resources/getData', [ResourceController::class, 'getData'])->name('resources.getData');
        // ✅ Route JSON
        Route::get('resources/json/{id}', [ResourceController::class, 'getResource'])
            ->name('resources.getById');
        // bulk - edit and delete
        Route::post('resources/bulk-delete', [ResourceController::class, 'bulkDelete'])
        ->name('resources.bulkDelete');
        Route::get('resources/bulk-edit', [ResourceController::class, 'bulkEditForm'])
        ->name('resources.bulkEdit');
        Route::post('resources/bulk-update', [ResourceController::class, 'bulkUpdate'])
        ->name('resources.bulkUpdate');

        Route::resource('resources', ResourceController::class)
            ->parameters(['resources' => 'resource']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('resources/import', [ResourceController::class, 'import'])->name('resources.import');
            Route::get('resources/export/{format}', [ResourceController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('resources.export');

        });

        Route::post('resources/data-calcul', [ResourceController::class, 'dataCalcul'])->name('resources.dataCalcul');
        Route::post('resources/update-attributes', [ResourceController::class, 'updateAttributes'])->name('resources.updateAttributes');

    

    });
});
