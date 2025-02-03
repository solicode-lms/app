<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgFormation\Controllers\FormateurController;

// routes for formateur management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgFormation')->group(function () {

        Route::get('formateurs/getFormateurs', [FormateurController::class, 'getFormateurs'])->name('formateurs.all');
        Route::resource('formateurs', FormateurController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('formateurs/export', [FormateurController::class, 'export'])->name('formateurs.export');
            Route::post('formateurs/import', [FormateurController::class, 'import'])->name('formateurs.import');
        });
    });
});
