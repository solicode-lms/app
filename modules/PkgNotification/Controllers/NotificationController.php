<?php


namespace Modules\PkgNotification\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgNotification\Controllers\Base\BaseNotificationController;

class NotificationController extends BaseNotificationController
{

    /**
     * @DynamicPermissionIgnore
     * Marque toutes les notifications de l'utilisateur courant comme lues.
     */
    public function markAllAsRead(Request $request)
    {
        if (!Auth::check()) {
            return JsonResponseHelper::error('Utilisateur non authentifié.', null, 401);
        }

        $userId = Auth::id();
        $this->notificationService->markAllAsReadForUser($userId);

        if ($request->ajax()) {
            return JsonResponseHelper::success('Toutes les notifications ont été marquées comme lues.');
        }

        return redirect()->route('notifications.index')->with(
            'success',
            'Toutes les notifications ont été marquées comme lues.'
        );
    }


   /**
     * @DynamicPermissionIgnore
     * Récupère les notifications de l'utilisateur et retourne le HTML pour le dropdown.
     */
    public function getUserNotifications(Request $request)
    {
        if (!Auth::check()) {
            return response('<span class="dropdown-item text-center text-muted">Non authentifié</span>', 401);
        }

        $userId = Auth::id();

        $notifications = $this->notificationService->getUnreadNotifications($userId, 5);
        $unreadNotificationCount = $this->notificationService->countUnreadNotifications($userId);

        return view('PkgNotification::notification._dropdown', compact('notifications', 'unreadNotificationCount'))->render();
    }

}
