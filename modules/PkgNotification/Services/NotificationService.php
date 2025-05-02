<?php

namespace Modules\PkgNotification\Services;

use Illuminate\Support\Str;
use Modules\PkgNotification\Services\Base\BaseNotificationService;
 

/**
 * Classe NotificationService pour gérer la persistance de l'entité Notification.
 */
class NotificationService extends BaseNotificationService
{

    // Ne pas recherche ou mettre  à jour le champs data
    protected $fieldsSearchable = [
        'user_id',
        'title',
        'message',
        'is_read',
        'type',
        'sent_at'
    ];


    public function prepareDataForIndexView(array $params = []): array{

        $this->viewState->init("filter.notification.is_read", 0);
        return parent::prepareDataForIndexView( $params);
    }

    /**
     * Calculs dynamiques sur l'entité Notification
     */
    public function dataCalcul($notification)
    {
        // En cas d'édition ou enrichissement de données
        if (isset($notification->id)) {
            $notification->is_read = (bool) $notification->is_read;
        }

        return $notification;
    }

    /**
     * Envoie une notification à un utilisateur
     * data doit contient : lien à visiter pour rendre la notification readed
     */
    public function sendNotification(int $userId, string $title, string $message, array $data = [], ?string $type = null)
    {

        // Exemple de data
        // [
        //     'lien' => route('realisationTaches.index',  ['contextKey' => 'realisationTache.index', 'action' => 'edit', 'id' => $realisationTache->id]),
        //     'realisationTache' => $realisationTache->id
        // ],

        // data doit contient : lien à visiter pour rendre la notification readed
        //   'lien' => route('realisationTaches.index',  ['contextKey' => 'realisationTache.index', 'action' => 'edit', 'id' => $realisationTache->id]),
        
        return $this->create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => empty($data) ? null : $data, // ✅ On passe l'array brut, Laravel gère grâce au cast
            'sent_at' => now(),
        ]);
    }


    /**
     * Envoie une notification liée à un modèle pour rendre les notifications "read" après visite.
     *
     * @param string $modelName Nom du modèle sans namespace (ex: "realisationTache")
     * @param int|string $modelId ID de l'entité concernée
     * @param int $userId ID de l'utilisateur cible
     * @param string|null $title Titre de la notification
     * @param string|null $message Message de la notification
     * @param string|null $type Type de notification (optionnel)
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function sendNotificationToReadData(
        string $modelName,
        int|string $modelId,
        int $userId,
        ?string $title = null,
        ?string $message = null,
        ?string $type = null
    ) {
        // Définir un titre par défaut si non fourni
        if (empty($title)) {
            $title = "Notification liée à " . Str::headline($modelName);
        }

        // Définir un message par défaut si non fourni
        if (empty($message)) {
            $message = "Une action a été réalisée sur " . Str::lower(Str::headline($modelName)) . ".";
        }

        // Construire dynamiquement la route d'édition
        $routeName = Str::camel(Str::plural($modelName)) . '.index'; // Ex: realisationTaches.index
        $contextKey = Str::camel($modelName) . '.index'; // Ex: realisationTache.index

        $data = [
            'lien' => route($routeName, [
                'contextKey' => $contextKey,
                'action' => 'edit',
                'id' => $modelId,
            ]),
            Str::camel($modelName) => $modelId,
        ];

        return $this->sendNotification(
            $userId,
            $title,
            $message,
            $data,
            $type
        );
    }



    /**
     * Marquer une notification comme lue
     */
    public function markAsRead(int $notificationId)
    {
        $notification = $this->find($notificationId);
        if ($notification && !$notification->is_read) {
            $notification->update(['is_read' => true]);
        }
    }

    /**
     * Marquer toutes les notifications comme lues pour l'utilisateur courant
     */
    public function markAllAsReadForUser(int $userId): void
    {
        $this->newQuery()
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * Récupérer les notifications non lues pour un utilisateur
     */
    public function getUnreadNotifications(int $userId, int $limit = 10)
    {
        return $this->newQuery()
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Compter les notifications non lues pour un utilisateur
     */
    public function countUnreadNotifications(int $userId): int
    {
        return $this->newQuery()
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }
}
