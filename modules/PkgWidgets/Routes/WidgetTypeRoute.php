<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgWidgets\Controllers\WidgetTypeController;

// routes for widgetType management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgWidgets')->group(function () {

        Route::get('widgetTypes/getWidgetTypes', [WidgetTypeController::class, 'getWidgetTypes'])->name('widgetTypes.all');
        Route::resource('widgetTypes', WidgetTypeController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('widgetTypes/export', [WidgetTypeController::class, 'export'])->name('widgetTypes.export');
            Route::post('widgetTypes/import', [WidgetTypeController::class, 'import'])->name('widgetTypes.import');
        });
    });
});
