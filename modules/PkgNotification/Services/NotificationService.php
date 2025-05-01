<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgNotification\Services;
use Modules\PkgNotification\Services\Base\BaseNotificationService;

/**
 * Classe NotificationService pour gérer la persistance de l'entité Notification.
 */
class NotificationService extends BaseNotificationService
{
    public function dataCalcul($notification)
    {
        // En Cas d'édit
        if(isset($notification->id)){
          
        }
      
        return $notification;
    }
   
}
