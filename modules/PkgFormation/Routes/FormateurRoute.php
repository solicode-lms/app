<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgFormation\Controllers\FormateurController;

// routes for formateur management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgFormation')->group(function () {
        Route::get('formateurs/getData', [FormateurController::class, 'getData'])->name('formateurs.getData');
        // ✅ Route JSON
        Route::get('formateurs/json/{id}', [FormateurController::class, 'getFormateur'])
            ->name('formateurs.getById');
        // bulk - edit and delete
        Route::post('formateurs/bulk-delete', [FormateurController::class, 'bulkDelete'])
        ->name('formateurs.bulkDelete');
        Route::get('formateurs/bulk-edit', [FormateurController::class, 'bulkEditForm'])
        ->name('formateurs.bulkEdit');
        Route::post('formateurs/bulk-update', [FormateurController::class, 'bulkUpdate'])
        ->name('formateurs.bulkUpdate');

        Route::resource('formateurs', FormateurController::class)
            ->parameters(['formateurs' => 'formateur']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('formateurs/import', [FormateurController::class, 'import'])->name('formateurs.import');
            Route::get('formateurs/export/{format}', [FormateurController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('formateurs.export');

        });

        Route::post('formateurs/data-calcul', [FormateurController::class, 'dataCalcul'])->name('formateurs.dataCalcul');
        Route::post('formateurs/update-attributes', [FormateurController::class, 'updateAttributes'])->name('formateurs.updateAttributes');
        Route::get('formateurs/initPassword/{id}', [FormateurController::class, 'initPassword'])->name('formateurs.initPassword');
    
    

    });
});
