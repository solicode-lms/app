<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgWidgets\Controllers\SectionWidgetController;

// routes for sectionWidget management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgWidgets')->group(function () {

        Route::get('sectionWidgets/getData', [SectionWidgetController::class, 'getData'])->name('sectionWidgets.getData');
        Route::resource('sectionWidgets', SectionWidgetController::class)
            ->parameters(['sectionWidgets' => 'sectionWidget']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('sectionWidgets/import', [SectionWidgetController::class, 'import'])->name('sectionWidgets.import');
            Route::get('sectionWidgets/export/{format}', [SectionWidgetController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('sectionWidgets.export');

        });

        Route::post('sectionWidgets/data-calcul', [SectionWidgetController::class, 'dataCalcul'])->name('sectionWidgets.dataCalcul');
        Route::post('sectionWidgets/update-attributes', [SectionWidgetController::class, 'updateAttributes'])->name('sectionWidgets.updateAttributes');

    

    });
});
