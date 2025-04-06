<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgGapp\Controllers\EPackageController;

// routes for ePackage management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgGapp')->group(function () {

        Route::get('ePackages/getData', [EPackageController::class, 'getData'])->name('ePackages.getData');
        Route::resource('ePackages', EPackageController::class)
            ->parameters(['ePackages' => 'ePackage']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('ePackages/import', [EPackageController::class, 'import'])->name('ePackages.import');
            Route::get('ePackages/export/{format}', [EPackageController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('ePackages.export');

        });

        Route::post('ePackages/data-calcul', [EPackageController::class, 'dataCalcul'])->name('ePackages.dataCalcul');
        Route::post('ePackages/update-attributes', [EPackageController::class, 'updateAttributes'])->name('ePackages.updateAttributes');

    

    });
});
