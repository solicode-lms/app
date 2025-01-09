<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGapp\Controllers\FieldTypeController;

// routes for fieldType management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGapp')->group(function () {

        Route::get('fieldTypes/getFieldTypes', [FieldTypeController::class, 'getFieldTypes'])->name('fieldTypes.all');
        Route::resource('fieldTypes', FieldTypeController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('fieldTypes/export', [FieldTypeController::class, 'export'])->name('fieldTypes.export');
            Route::post('fieldTypes/import', [FieldTypeController::class, 'import'])->name('fieldTypes.import');
        });
    });
});
