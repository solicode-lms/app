<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgWidgets\Controllers\WidgetOperationController;

// routes for widgetOperation management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgWidgets')->group(function () {

        Route::get('widgetOperations/getData', [WidgetOperationController::class, 'getData'])->name('widgetOperations.getData');
        Route::resource('widgetOperations', WidgetOperationController::class)
            ->parameters(['widgetOperations' => 'widgetOperation']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('widgetOperations/import', [WidgetOperationController::class, 'import'])->name('widgetOperations.import');
            Route::get('widgetOperations/export/{format}', [WidgetOperationController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('widgetOperations.export');

        });

        Route::post('widgetOperations/data-calcul', [WidgetOperationController::class, 'dataCalcul'])->name('widgetOperations.dataCalcul');
        Route::post('widgetOperations/update-attributes', [WidgetOperationController::class, 'updateAttributes'])->name('widgetOperations.updateAttributes');

    

    });
});
