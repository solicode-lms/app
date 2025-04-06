<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



use Illuminate\Support\Facades\Route;
use Modules\PkgWidgets\Controllers\WidgetUtilisateurController;

// routes for widgetUtilisateur management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgWidgets')->group(function () {

        Route::get('widgetUtilisateurs/getData', [WidgetUtilisateurController::class, 'getData'])->name('widgetUtilisateurs.getData');
        Route::resource('widgetUtilisateurs', WidgetUtilisateurController::class)
            ->parameters(['widgetUtilisateurs' => 'widgetUtilisateur']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('widgetUtilisateurs/import', [WidgetUtilisateurController::class, 'import'])->name('widgetUtilisateurs.import');
            Route::get('widgetUtilisateurs/export/{format}', [WidgetUtilisateurController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('widgetUtilisateurs.export');

        });

        Route::post('widgetUtilisateurs/data-calcul', [WidgetUtilisateurController::class, 'dataCalcul'])->name('widgetUtilisateurs.dataCalcul');
        Route::post('widgetUtilisateurs/update-attributes', [WidgetUtilisateurController::class, 'updateAttributes'])->name('widgetUtilisateurs.updateAttributes');

    

    });
});
