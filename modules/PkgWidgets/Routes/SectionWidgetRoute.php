<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgWidgets\Controllers\SectionWidgetController;

// routes for sectionWidget management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgWidgets')->group(function () {

        // Edition inline
        Route::get('sectionWidgets/{id}/field/{field}/meta', [SectionWidgetController::class, 'fieldMeta'])
            ->name('sectionWidgets.field.meta');
        Route::patch('sectionWidgets/{id}/inline', [SectionWidgetController::class, 'patchInline'])
            ->name('sectionWidgets.patchInline');

        Route::get('sectionWidgets/getData', [SectionWidgetController::class, 'getData'])->name('sectionWidgets.getData');
        // ✅ Route JSON
        Route::get('sectionWidgets/json/{id}', [SectionWidgetController::class, 'getSectionWidget'])
            ->name('sectionWidgets.getById');
        // bulk - edit and delete
        Route::post('sectionWidgets/bulk-delete', [SectionWidgetController::class, 'bulkDelete'])
        ->name('sectionWidgets.bulkDelete');
        Route::get('sectionWidgets/bulk-edit', [SectionWidgetController::class, 'bulkEditForm'])
        ->name('sectionWidgets.bulkEdit');
        Route::post('sectionWidgets/bulk-update', [SectionWidgetController::class, 'bulkUpdate'])
        ->name('sectionWidgets.bulkUpdate');

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
