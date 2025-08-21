<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgSessions\Controllers\LivrableSessionController;

// routes for livrableSession management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgSessions')->group(function () {

        // Edition inline
        Route::get('livrableSessions/{id}/field/{field}/meta', [LivrableSessionController::class, 'fieldMeta'])
            ->name('livrableSessions.field.meta');
        Route::patch('livrableSessions/{id}/inline', [LivrableSessionController::class, 'patchInline'])
            ->name('livrableSessions.patchInline');

        Route::get('livrableSessions/getData', [LivrableSessionController::class, 'getData'])->name('livrableSessions.getData');
        // ✅ Route JSON
        Route::get('livrableSessions/json/{id}', [LivrableSessionController::class, 'getLivrableSession'])
            ->name('livrableSessions.getById');
        // bulk - edit and delete
        Route::post('livrableSessions/bulk-delete', [LivrableSessionController::class, 'bulkDelete'])
        ->name('livrableSessions.bulkDelete');
        Route::get('livrableSessions/bulk-edit', [LivrableSessionController::class, 'bulkEditForm'])
        ->name('livrableSessions.bulkEdit');
        Route::post('livrableSessions/bulk-update', [LivrableSessionController::class, 'bulkUpdate'])
        ->name('livrableSessions.bulkUpdate');

        Route::resource('livrableSessions', LivrableSessionController::class)
            ->parameters(['livrableSessions' => 'livrableSession']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('livrableSessions/import', [LivrableSessionController::class, 'import'])->name('livrableSessions.import');
            Route::get('livrableSessions/export/{format}', [LivrableSessionController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('livrableSessions.export');

        });

        Route::post('livrableSessions/data-calcul', [LivrableSessionController::class, 'dataCalcul'])->name('livrableSessions.dataCalcul');
        Route::post('livrableSessions/update-attributes', [LivrableSessionController::class, 'updateAttributes'])->name('livrableSessions.updateAttributes');

    

    });
});
