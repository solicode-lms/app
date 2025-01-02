<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCreationProjet\Controllers\LivrableController;

// routes for livrable management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCreationProjet')->group(function () {

        Route::get('livrables/getLivrables', [LivrableController::class, 'getLivrables'])->name('livrables.all');
        Route::resource('livrables', LivrableController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('livrables/export', [LivrableController::class, 'export'])->name('livrables.export');
            Route::post('livrables/import', [LivrableController::class, 'import'])->name('livrables.import');
        });
    });
});
