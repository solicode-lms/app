<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCompetences\Controllers\TechnologyController;

// routes for technology management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCompetences')->group(function () {

        Route::get('technologies/getTechnologies', [TechnologyController::class, 'getTechnologies'])->name('technologies.all');
        Route::resource('technologies', TechnologyController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('technologies/export', [TechnologyController::class, 'export'])->name('technologies.export');
            Route::post('technologies/import', [TechnologyController::class, 'import'])->name('technologies.import');
        });
    });
});
