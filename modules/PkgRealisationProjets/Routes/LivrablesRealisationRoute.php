<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgRealisationProjets\Controllers\LivrablesRealisationController;

// routes for livrablesRealisation management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgRealisationProjets')->group(function () {

        Route::get('livrablesRealisations/getLivrablesRealisations', [LivrablesRealisationController::class, 'getLivrablesRealisations'])->name('livrablesRealisations.all');
        Route::resource('livrablesRealisations', LivrablesRealisationController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('livrablesRealisations/export', [LivrablesRealisationController::class, 'export'])->name('livrablesRealisations.export');
            Route::post('livrablesRealisations/import', [LivrablesRealisationController::class, 'import'])->name('livrablesRealisations.import');
        });
    });
});
