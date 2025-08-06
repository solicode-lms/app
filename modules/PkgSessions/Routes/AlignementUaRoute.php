<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgSessions\Controllers\AlignementUaController;

// routes for alignementUa management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgSessions')->group(function () {
        Route::get('alignementUas/getData', [AlignementUaController::class, 'getData'])->name('alignementUas.getData');
        // ✅ Route JSON
        Route::get('alignementUas/json/{id}', [AlignementUaController::class, 'getAlignementUa'])
            ->name('alignementUas.getById');
        // bulk - edit and delete
        Route::post('alignementUas/bulk-delete', [AlignementUaController::class, 'bulkDelete'])
        ->name('alignementUas.bulkDelete');
        Route::get('alignementUas/bulk-edit', [AlignementUaController::class, 'bulkEditForm'])
        ->name('alignementUas.bulkEdit');
        Route::post('alignementUas/bulk-update', [AlignementUaController::class, 'bulkUpdate'])
        ->name('alignementUas.bulkUpdate');

        Route::resource('alignementUas', AlignementUaController::class)
            ->parameters(['alignementUas' => 'alignementUa']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('alignementUas/import', [AlignementUaController::class, 'import'])->name('alignementUas.import');
            Route::get('alignementUas/export/{format}', [AlignementUaController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('alignementUas.export');

        });

        Route::post('alignementUas/data-calcul', [AlignementUaController::class, 'dataCalcul'])->name('alignementUas.dataCalcul');
        Route::post('alignementUas/update-attributes', [AlignementUaController::class, 'updateAttributes'])->name('alignementUas.updateAttributes');

    

    });
});
