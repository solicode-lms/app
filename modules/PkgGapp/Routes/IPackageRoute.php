<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGapp\Controllers\IPackageController;

// routes for iPackage management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGapp')->group(function () {

        Route::get('iPackages/getIPackages', [IPackageController::class, 'getIPackages'])->name('iPackages.all');
        Route::resource('iPackages', IPackageController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('iPackages/export', [IPackageController::class, 'export'])->name('iPackages.export');
            Route::post('iPackages/import', [IPackageController::class, 'import'])->name('iPackages.import');
        });
    });
});
