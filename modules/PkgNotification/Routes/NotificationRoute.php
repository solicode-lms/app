<?php
// Ce fichier est maintenu par ESSARRAJ getUserNotifications



use Illuminate\Support\Facades\Route;
use Modules\PkgNotification\Controllers\NotificationController;

// routes for notification management
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgNotification')->group(function () {


        
        Route::get('notifications/markAllAsRead', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
        
        Route::get('notifications/getUserNotifications', [NotificationController::class, 'getUserNotifications'])->name('notifications.getUserNotifications');


        Route::get('notifications/getData', [NotificationController::class, 'getData'])->name('notifications.getData');
        // bulk - edit and delete
        Route::post('notifications/bulk-delete', [NotificationController::class, 'bulkDelete'])
        ->name('notifications.bulkDelete');
        Route::get('notifications/bulk-edit', [NotificationController::class, 'bulkEditForm'])
        ->name('notifications.bulkEdit');
        Route::post('notifications/bulk-update', [NotificationController::class, 'bulkUpdate'])
        ->name('notifications.bulkUpdate');

        Route::resource('notifications', NotificationController::class)
            ->parameters(['notifications' => 'notification']);
        // Routes supplémentaires avec préfixe
        Route::prefix('data')->group(function () {
            Route::post('notifications/import', [NotificationController::class, 'import'])->name('notifications.import');
            Route::get('notifications/export/{format}', [NotificationController::class, 'export'])
            ->where('format', 'csv|xlsx')
            ->name('notifications.export');

        });

        Route::post('notifications/data-calcul', [NotificationController::class, 'dataCalcul'])->name('notifications.dataCalcul');
        Route::post('notifications/update-attributes', [NotificationController::class, 'updateAttributes'])->name('notifications.updateAttributes');

    

    });
});
