<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCreationProjet\Controllers\NatureLivrableController;

// routes for natureLivrable management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCreationProjet')->group(function () {

        Route::get('natureLivrables/getNatureLivrables', [NatureLivrableController::class, 'getNatureLivrables'])->name('natureLivrables.all');
        Route::resource('natureLivrables', NatureLivrableController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('natureLivrables/export', [NatureLivrableController::class, 'export'])->name('natureLivrables.export');
            Route::post('natureLivrables/import', [NatureLivrableController::class, 'import'])->name('natureLivrables.import');
        });
    });
});
