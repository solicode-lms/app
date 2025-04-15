<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgAutorisation\Controllers\ProfileController;

// routes for profile management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgAutorisation')->group(function () {

        Route::get('profiles/getData', [ProfileController::class, 'getData'])->name('profiles.getData');
        // bulk - edit and delete
        Route::post('profiles/bulk-delete', [ProfileController::class, 'bulkDelete'])
        ->name('profiles.bulkDelete');
        Route::get('profiles/bulk-edit', [ProfileController::class, 'bulkEditForm'])
        ->name('profiles.bulkEdit');
        Route::post('profiles/bulk-update', [ProfileController::class, 'bulkUpdate'])
        ->name('profiles.bulkUpdate');

        Route::resource('profiles', ProfileController::class)
            ->parameters(['profiles' => 'profile']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('profiles/import', [ProfileController::class, 'import'])->name('profiles.import');
            Route::get('profiles/export/{format}', [ProfileController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('profiles.export');

        });

        Route::post('profiles/data-calcul', [ProfileController::class, 'dataCalcul'])->name('profiles.dataCalcul');
        Route::post('profiles/update-attributes', [ProfileController::class, 'updateAttributes'])->name('profiles.updateAttributes');

    

    });
});
