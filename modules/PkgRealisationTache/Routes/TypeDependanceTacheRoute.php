<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgRealisationTache\Controllers\TypeDependanceTacheController;

// routes for typeDependanceTache management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgRealisationTache')->group(function () {
        Route::get('typeDependanceTaches/getData', [TypeDependanceTacheController::class, 'getData'])->name('typeDependanceTaches.getData');
        // bulk - edit and delete
        Route::post('typeDependanceTaches/bulk-delete', [TypeDependanceTacheController::class, 'bulkDelete'])
        ->name('typeDependanceTaches.bulkDelete');
        Route::get('typeDependanceTaches/bulk-edit', [TypeDependanceTacheController::class, 'bulkEditForm'])
        ->name('typeDependanceTaches.bulkEdit');
        Route::post('typeDependanceTaches/bulk-update', [TypeDependanceTacheController::class, 'bulkUpdate'])
        ->name('typeDependanceTaches.bulkUpdate');

        Route::resource('typeDependanceTaches', TypeDependanceTacheController::class)
            ->parameters(['typeDependanceTaches' => 'typeDependanceTache']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('typeDependanceTaches/import', [TypeDependanceTacheController::class, 'import'])->name('typeDependanceTaches.import');
            Route::get('typeDependanceTaches/export/{format}', [TypeDependanceTacheController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('typeDependanceTaches.export');

        });

        Route::post('typeDependanceTaches/data-calcul', [TypeDependanceTacheController::class, 'dataCalcul'])->name('typeDependanceTaches.dataCalcul');
        Route::post('typeDependanceTaches/update-attributes', [TypeDependanceTacheController::class, 'updateAttributes'])->name('typeDependanceTaches.updateAttributes');

    

    });
});
