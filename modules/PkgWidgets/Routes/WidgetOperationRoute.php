<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgWidgets\Controllers\WidgetOperationController;

// routes for widgetOperation management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgWidgets')->group(function () {

        Route::get('widgetOperations/getWidgetOperations', [WidgetOperationController::class, 'getWidgetOperations'])->name('widgetOperations.all');
        Route::resource('widgetOperations', WidgetOperationController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('widgetOperations/export', [WidgetOperationController::class, 'export'])->name('widgetOperations.export');
            Route::post('widgetOperations/import', [WidgetOperationController::class, 'import'])->name('widgetOperations.import');
        });
    });
});
