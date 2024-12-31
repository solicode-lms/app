<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgCreationProjet\Controllers\LivrableController;

// routes for livrable management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgCreationProjet')->group(function () {

        Route::get('livrables/getLivrables', [LivrableController::class, 'getLivrables'])->name('livrables.all');
        
        Route::resource('livrables', LivrableController::class);

        /** */
   
        // Route pour les livrables avec scoping facultatif
        Route::prefix('livrables')->group(function () {
            Route::get('/{scop_entity?}/{scop_id?}', [LivrableController::class, 'index'])->name('livrables.index');
            Route::post('/{scop_entity?}/{scop_id?}', [LivrableController::class, 'store'])->name('livrables.store');
            Route::get('/create/{scop_entity?}/{scop_id?}', [LivrableController::class, 'create'])->name('livrables.create');
            Route::get('/{livrable}/{scop_entity?}/{scop_id?}', [LivrableController::class, 'show'])->name('livrables.show');
            Route::put('/{livrable}/{scop_entity?}/{scop_id?}', [LivrableController::class, 'update'])->name('livrables.update');
            Route::delete('/{livrable}/{scop_entity?}/{scop_id?}', [LivrableController::class, 'destroy'])->name('livrables.destroy');
            Route::get('/{livrable}/edit/{scop_entity?}/{scop_id?}', [LivrableController::class, 'edit'])->name('livrables.edit');
        });
   


        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('livrables/export', [LivrableController::class, 'export'])->name('livrables.export');
            Route::post('livrables/import', [LivrableController::class, 'import'])->name('livrables.import');
        });
    });
});
