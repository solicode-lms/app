<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgWidgets\Controllers\WidgetController;

// routes for widget management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgWidgets')->group(function () {
        Route::get('widgets/getData', [WidgetController::class, 'getData'])->name('widgets.getData');
        // bulk - edit and delete
        Route::post('widgets/bulk-delete', [WidgetController::class, 'bulkDelete'])
        ->name('widgets.bulkDelete');
        Route::get('widgets/bulk-edit', [WidgetController::class, 'bulkEditForm'])
        ->name('widgets.bulkEdit');
        Route::post('widgets/bulk-update', [WidgetController::class, 'bulkUpdate'])
        ->name('widgets.bulkUpdate');

        Route::resource('widgets', WidgetController::class)
            ->parameters(['widgets' => 'widget']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('widgets/import', [WidgetController::class, 'import'])->name('widgets.import');
            Route::get('widgets/export/{format}', [WidgetController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('widgets.export');

        });

        Route::post('widgets/data-calcul', [WidgetController::class, 'dataCalcul'])->name('widgets.dataCalcul');
        Route::post('widgets/update-attributes', [WidgetController::class, 'updateAttributes'])->name('widgets.updateAttributes');

    

    });
});
