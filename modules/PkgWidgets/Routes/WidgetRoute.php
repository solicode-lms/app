<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgWidgets\Controllers\WidgetController;

// routes for widget management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgWidgets')->group(function () {

        Route::get('widgets/getWidgets', [WidgetController::class, 'getWidgets'])->name('widgets.all');
        Route::resource('widgets', WidgetController::class);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::get('widgets/export', [WidgetController::class, 'export'])->name('widgets.export');
            Route::post('widgets/import', [WidgetController::class, 'import'])->name('widgets.import');
        });
    });
});
