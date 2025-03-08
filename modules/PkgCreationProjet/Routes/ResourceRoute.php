<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCreationProjet\Controllers\ResourceController;

// routes for resource management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCreationProjet')->group(function () {

        Route::get('resources/getData', [ResourceController::class, 'getData'])->name('resources.getData');
        Route::resource('resources', ResourceController::class)
            ->parameters(['resources' => 'resource']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('resources/import', [ResourceController::class, 'import'])->name('resources.import');
            Route::get('resources/export/{format}', [ResourceController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('resources.export');

        });

        Route::post('resources/data-calcul', [ResourceController::class, 'dataCalcul'])->name('resources.dataCalcul');

    });
});
