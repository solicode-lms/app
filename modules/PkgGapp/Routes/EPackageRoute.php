<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGapp\Controllers\EPackageController;

// routes for ePackage management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGapp')->group(function () {

        Route::get('ePackages/getEPackages', [EPackageController::class, 'getEPackages'])->name('ePackages.all');
        Route::resource('ePackages', EPackageController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('ePackages/export', [EPackageController::class, 'export'])->name('ePackages.export');
            Route::post('ePackages/import', [EPackageController::class, 'import'])->name('ePackages.import');
        });
    });
});
