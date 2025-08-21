<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgApprentissage\Controllers\EtatRealisationModuleController;

// routes for etatRealisationModule management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgApprentissage')->group(function () {

        // Edition inline
        Route::get('etatRealisationModules/{id}/field/{field}/meta', [EtatRealisationModuleController::class, 'fieldMeta'])
            ->name('etatRealisationModules.field.meta');
        Route::patch('etatRealisationModules/{id}/inline', [EtatRealisationModuleController::class, 'patchInline'])
            ->name('etatRealisationModules.patchInline');

        Route::get('etatRealisationModules/getData', [EtatRealisationModuleController::class, 'getData'])->name('etatRealisationModules.getData');
        // ✅ Route JSON
        Route::get('etatRealisationModules/json/{id}', [EtatRealisationModuleController::class, 'getEtatRealisationModule'])
            ->name('etatRealisationModules.getById');
        // bulk - edit and delete
        Route::post('etatRealisationModules/bulk-delete', [EtatRealisationModuleController::class, 'bulkDelete'])
        ->name('etatRealisationModules.bulkDelete');
        Route::get('etatRealisationModules/bulk-edit', [EtatRealisationModuleController::class, 'bulkEditForm'])
        ->name('etatRealisationModules.bulkEdit');
        Route::post('etatRealisationModules/bulk-update', [EtatRealisationModuleController::class, 'bulkUpdate'])
        ->name('etatRealisationModules.bulkUpdate');

        Route::resource('etatRealisationModules', EtatRealisationModuleController::class)
            ->parameters(['etatRealisationModules' => 'etatRealisationModule']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('etatRealisationModules/import', [EtatRealisationModuleController::class, 'import'])->name('etatRealisationModules.import');
            Route::get('etatRealisationModules/export/{format}', [EtatRealisationModuleController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('etatRealisationModules.export');

        });

        Route::post('etatRealisationModules/data-calcul', [EtatRealisationModuleController::class, 'dataCalcul'])->name('etatRealisationModules.dataCalcul');
        Route::post('etatRealisationModules/update-attributes', [EtatRealisationModuleController::class, 'updateAttributes'])->name('etatRealisationModules.updateAttributes');

    

    });
});
