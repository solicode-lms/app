<?php

use Illuminate\Support\Facades\Route;
use Modules\PkgNotification\Controllers\NotificationController;

Route::middleware('auth')
    ->prefix('/admin/PkgNotification')
    ->group(function () {
        Route::get('notifications/getUserNotifications', [NotificationController::class, 'getUserNotifications'])
            ->name('notifications.getUserNotifications');
    });