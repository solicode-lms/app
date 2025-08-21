<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgFormation\Controllers\FiliereController;

// routes for filiere management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgFormation')->group(function () {

        // Edition inline
        Route::get('filieres/{id}/field/{field}/meta', [FiliereController::class, 'fieldMeta'])
            ->name('filieres.field.meta');
        Route::patch('filieres/{id}/inline', [FiliereController::class, 'patchInline'])
            ->name('filieres.patchInline');

        Route::get('filieres/getData', [FiliereController::class, 'getData'])->name('filieres.getData');
        // ✅ Route JSON
        Route::get('filieres/json/{id}', [FiliereController::class, 'getFiliere'])
            ->name('filieres.getById');
        // bulk - edit and delete
        Route::post('filieres/bulk-delete', [FiliereController::class, 'bulkDelete'])
        ->name('filieres.bulkDelete');
        Route::get('filieres/bulk-edit', [FiliereController::class, 'bulkEditForm'])
        ->name('filieres.bulkEdit');
        Route::post('filieres/bulk-update', [FiliereController::class, 'bulkUpdate'])
        ->name('filieres.bulkUpdate');

        Route::resource('filieres', FiliereController::class)
            ->parameters(['filieres' => 'filiere']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('filieres/import', [FiliereController::class, 'import'])->name('filieres.import');
            Route::get('filieres/export/{format}', [FiliereController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('filieres.export');

        });

        Route::post('filieres/data-calcul', [FiliereController::class, 'dataCalcul'])->name('filieres.dataCalcul');
        Route::post('filieres/update-attributes', [FiliereController::class, 'updateAttributes'])->name('filieres.updateAttributes');

    

    });
});
