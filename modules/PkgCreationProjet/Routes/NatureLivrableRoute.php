<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCreationProjet\Controllers\NatureLivrableController;

// routes for natureLivrable management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCreationProjet')->group(function () {

        Route::get('natureLivrables/getNatureLivrables', [NatureLivrableController::class, 'getNatureLivrables'])->name('natureLivrables.all');
        Route::resource('natureLivrables', NatureLivrableController::class)
            ->parameters(['natureLivrables' => 'natureLivrable']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('natureLivrables/import', [NatureLivrableController::class, 'import'])->name('natureLivrables.import');
            Route::get('natureLivrables/export/{format}', [NatureLivrableController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('natureLivrables.export');

        });

        Route::post('natureLivrables/data-calcul', [NatureLivrableController::class, 'dataCalcul'])->name('natureLivrables.dataCalcul');

    });
});
