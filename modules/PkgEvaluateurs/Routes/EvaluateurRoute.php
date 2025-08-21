<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgEvaluateurs\Controllers\EvaluateurController;

// routes for evaluateur management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgEvaluateurs')->group(function () {

        // Edition inline
        Route::get('evaluateurs/{id}/field/{field}/meta', [EvaluateurController::class, 'fieldMeta'])
            ->name('evaluateurs.field.meta');
        Route::patch('evaluateurs/{id}/inline', [EvaluateurController::class, 'patchInline'])
            ->name('evaluateurs.patchInline');

        Route::get('evaluateurs/getData', [EvaluateurController::class, 'getData'])->name('evaluateurs.getData');
        // ✅ Route JSON
        Route::get('evaluateurs/json/{id}', [EvaluateurController::class, 'getEvaluateur'])
            ->name('evaluateurs.getById');
        // bulk - edit and delete
        Route::post('evaluateurs/bulk-delete', [EvaluateurController::class, 'bulkDelete'])
        ->name('evaluateurs.bulkDelete');
        Route::get('evaluateurs/bulk-edit', [EvaluateurController::class, 'bulkEditForm'])
        ->name('evaluateurs.bulkEdit');
        Route::post('evaluateurs/bulk-update', [EvaluateurController::class, 'bulkUpdate'])
        ->name('evaluateurs.bulkUpdate');

        Route::resource('evaluateurs', EvaluateurController::class)
            ->parameters(['evaluateurs' => 'evaluateur']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('evaluateurs/import', [EvaluateurController::class, 'import'])->name('evaluateurs.import');
            Route::get('evaluateurs/export/{format}', [EvaluateurController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('evaluateurs.export');

        });

        Route::post('evaluateurs/data-calcul', [EvaluateurController::class, 'dataCalcul'])->name('evaluateurs.dataCalcul');
        Route::post('evaluateurs/update-attributes', [EvaluateurController::class, 'updateAttributes'])->name('evaluateurs.updateAttributes');
        Route::get('evaluateurs/initPassword/{id}', [EvaluateurController::class, 'initPassword'])->name('evaluateurs.initPassword');
    
    

    });
});
