<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\Core\Controllers\UserModelFilterController;

// routes for userModelFilter management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/Core')->group(function () {

        Route::get('userModelFilters/getData', [UserModelFilterController::class, 'getData'])->name('userModelFilters.getData');
        // bulk - edit and delete
        Route::post('userModelFilters/bulk-delete', [UserModelFilterController::class, 'bulkDelete'])
        ->name('userModelFilters.bulkDelete');
        Route::get('userModelFilters/bulk-edit', [UserModelFilterController::class, 'bulkEditForm'])
        ->name('userModelFilters.bulkEdit');
        Route::post('userModelFilters/bulk-update', [UserModelFilterController::class, 'bulkUpdate'])
        ->name('userModelFilters.bulkUpdate');

        Route::resource('userModelFilters', UserModelFilterController::class)
            ->parameters(['userModelFilters' => 'userModelFilter']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('userModelFilters/import', [UserModelFilterController::class, 'import'])->name('userModelFilters.import');
            Route::get('userModelFilters/export/{format}', [UserModelFilterController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('userModelFilters.export');

        });

        Route::post('userModelFilters/data-calcul', [UserModelFilterController::class, 'dataCalcul'])->name('userModelFilters.dataCalcul');
        Route::post('userModelFilters/update-attributes', [UserModelFilterController::class, 'updateAttributes'])->name('userModelFilters.updateAttributes');

    

    });
});
