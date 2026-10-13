<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgTableauBord\Controllers\RemediationController;

// routes for remediation management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgTableauBord')->group(function () {

        // Edition inline
        Route::get('remediations/{id}/field/{field}/meta', [RemediationController::class, 'fieldMeta'])
            ->name('remediations.field.meta');
        Route::patch('remediations/{id}/inline', [RemediationController::class, 'patchInline'])
            ->name('remediations.patchInline');

        Route::get('remediations/getData', [RemediationController::class, 'getData'])->name('remediations.getData');
        // ✅ Route JSON
        Route::get('remediations/json/{id}', [RemediationController::class, 'getRemediation'])
            ->name('remediations.getById');
        // bulk - edit and delete
        Route::post('remediations/bulk-delete', [RemediationController::class, 'bulkDelete'])
        ->name('remediations.bulkDelete');
        Route::get('remediations/bulk-edit', [RemediationController::class, 'bulkEditForm'])
        ->name('remediations.bulkEdit');
        Route::post('remediations/bulk-update', [RemediationController::class, 'bulkUpdate'])
        ->name('remediations.bulkUpdate');

        Route::resource('remediations', RemediationController::class)
            ->parameters(['remediations' => 'remediation']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('remediations/import', [RemediationController::class, 'import'])->name('remediations.import');
            Route::get('remediations/export/{format}', [RemediationController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('remediations.export');

        });

        Route::post('remediations/data-calcul', [RemediationController::class, 'dataCalcul'])->name('remediations.dataCalcul');
        Route::post('remediations/update-attributes', [RemediationController::class, 'updateAttributes'])->name('remediations.updateAttributes');

    

    });
});
