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

}
