<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgWidgets\Controllers\WidgetTypeController;

// routes for widgetType management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgWidgets')->group(function () {
        Route::get('widgetTypes/getData', [WidgetTypeController::class, 'getData'])->name('widgetTypes.getData');
        // bulk - edit and delete
        Route::post('widgetTypes/bulk-delete', [WidgetTypeController::class, 'bulkDelete'])
        ->name('widgetTypes.bulkDelete');
        Route::get('widgetTypes/bulk-edit', [WidgetTypeController::class, 'bulkEditForm'])
        ->name('widgetTypes.bulkEdit');
        Route::post('widgetTypes/bulk-update', [WidgetTypeController::class, 'bulkUpdate'])
        ->name('widgetTypes.bulkUpdate');

        Route::resource('widgetTypes', WidgetTypeController::class)
            ->parameters(['widgetTypes' => 'widgetType']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('widgetTypes/import', [WidgetTypeController::class, 'import'])->name('widgetTypes.import');
            Route::get('widgetTypes/export/{format}', [WidgetTypeController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('widgetTypes.export');

        });

        Route::post('widgetTypes/data-calcul', [WidgetTypeController::class, 'dataCalcul'])->name('widgetTypes.dataCalcul');
        Route::post('widgetTypes/update-attributes', [WidgetTypeController::class, 'updateAttributes'])->name('widgetTypes.updateAttributes');

    

    });
});
