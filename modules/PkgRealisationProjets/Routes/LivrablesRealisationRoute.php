<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgRealisationProjets\Controllers\LivrablesRealisationController;

// routes for livrablesRealisation management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgRealisationProjets')->group(function () {
        Route::get('livrablesRealisations/getData', [LivrablesRealisationController::class, 'getData'])->name('livrablesRealisations.getData');
        // ✅ Route JSON
        Route::get('livrablesRealisations/json/{id}', [LivrablesRealisationController::class, 'getLivrablesRealisation'])
            ->name('livrablesRealisations.getById');
        // bulk - edit and delete
        Route::post('livrablesRealisations/bulk-delete', [LivrablesRealisationController::class, 'bulkDelete'])
        ->name('livrablesRealisations.bulkDelete');
        Route::get('livrablesRealisations/bulk-edit', [LivrablesRealisationController::class, 'bulkEditForm'])
        ->name('livrablesRealisations.bulkEdit');
        Route::post('livrablesRealisations/bulk-update', [LivrablesRealisationController::class, 'bulkUpdate'])
        ->name('livrablesRealisations.bulkUpdate');

        Route::resource('livrablesRealisations', LivrablesRealisationController::class)
            ->parameters(['livrablesRealisations' => 'livrablesRealisation']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('livrablesRealisations/import', [LivrablesRealisationController::class, 'import'])->name('livrablesRealisations.import');
            Route::get('livrablesRealisations/export/{format}', [LivrablesRealisationController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('livrablesRealisations.export');

        });

        Route::post('livrablesRealisations/data-calcul', [LivrablesRealisationController::class, 'dataCalcul'])->name('livrablesRealisations.dataCalcul');
        Route::post('livrablesRealisations/update-attributes', [LivrablesRealisationController::class, 'updateAttributes'])->name('livrablesRealisations.updateAttributes');

    

    });
});
