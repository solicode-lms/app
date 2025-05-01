<?php

namespace Modules\Core\App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Modules\PkgNotification\Services\NotificationService;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                /** @var NotificationService $notificationService */
                $notificationService = app(NotificationService::class);

                $userId = Auth::id();

                // ✅ Utiliser les méthodes du Service au lieu de faire des query() manuelles
                $notifications = $notificationService->getUnreadNotifications($userId, 5);
                $unreadNotificationCount = $notificationService->countUnreadNotifications($userId);

                $view->with([
                    'notifications' => $notifications,
                    'unreadNotificationCount' => $unreadNotificationCount,
                ]);
            }
        });
    }
}
