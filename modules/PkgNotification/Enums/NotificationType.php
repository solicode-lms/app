<?php

namespace Modules\PkgNotification\Enums;

/**
 * Enumération des types de Notifications.
 */
enum NotificationType: string
{
    case NOUVELLE_TACHE = 'nouvelle_tache';
    case TACHE_VALIDEE = 'tache_validee';
    case TACHE_REJETEE = 'tache_rejetee';
    case NOUVEAU_PROJET = 'nouveau_projet';
    case FEEDBACK_FORMATEUR = 'feedback_formateur';
    case DEADLINE_PROCHE = 'deadline_proche';
    case AUTOFORMATION_DISPONIBLE = 'autoformation_disponible';
    case BADGE_OBTENU = 'badge_obtenu';
    case MESSAGE_GENERAL = 'message_general'; // Notification sans contexte spécifique

    /**
     * Récupérer le libellé humain du type de notification.
     */
    public function label(): string
    {
        return match($this) {
            self::NOUVELLE_TACHE => 'Nouvelle tâche assignée',
            self::TACHE_VALIDEE => 'Tâche validée',
            self::TACHE_REJETEE => 'Tâche rejetée',
            self::NOUVEAU_PROJET => 'Nouveau projet de réalisation',
            self::FEEDBACK_FORMATEUR => 'Nouveau feedback reçu',
            self::DEADLINE_PROCHE => 'Deadline imminente',
            self::AUTOFORMATION_DISPONIBLE => 'Nouvelle auto-formation disponible',
            self::BADGE_OBTENU => 'Nouveau badge obtenu',
            self::MESSAGE_GENERAL => 'Notification générale',
        };
    }
}
