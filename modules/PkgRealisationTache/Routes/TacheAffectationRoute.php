<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgRealisationTache\Controllers\TacheAffectationController;

// routes for tacheAffectation management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgRealisationTache')->group(function () {

        // Edition inline
        Route::get('tacheAffectations/{id}/field/{field}/meta', [TacheAffectationController::class, 'fieldMeta'])
            ->name('tacheAffectations.field.meta');
        Route::patch('tacheAffectations/{id}/inline', [TacheAffectationController::class, 'patchInline'])
            ->name('tacheAffectations.patchInline');

        Route::get('tacheAffectations/getData', [TacheAffectationController::class, 'getData'])->name('tacheAffectations.getData');
        // ✅ Route JSON
        Route::get('tacheAffectations/json/{id}', [TacheAffectationController::class, 'getTacheAffectation'])
            ->name('tacheAffectations.getById');
        // bulk - edit and delete
        Route::post('tacheAffectations/bulk-delete', [TacheAffectationController::class, 'bulkDelete'])
        ->name('tacheAffectations.bulkDelete');
        Route::get('tacheAffectations/bulk-edit', [TacheAffectationController::class, 'bulkEditForm'])
        ->name('tacheAffectations.bulkEdit');
        Route::post('tacheAffectations/bulk-update', [TacheAffectationController::class, 'bulkUpdate'])
        ->name('tacheAffectations.bulkUpdate');

        Route::resource('tacheAffectations', TacheAffectationController::class)
            ->parameters(['tacheAffectations' => 'tacheAffectation']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('tacheAffectations/import', [TacheAffectationController::class, 'import'])->name('tacheAffectations.import');
            Route::get('tacheAffectations/export/{format}', [TacheAffectationController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('tacheAffectations.export');

        });

        Route::post('tacheAffectations/data-calcul', [TacheAffectationController::class, 'dataCalcul'])->name('tacheAffectations.dataCalcul');
        Route::post('tacheAffectations/update-attributes', [TacheAffectationController::class, 'updateAttributes'])->name('tacheAffectations.updateAttributes');

    

    });
});
