<?php

namespace Modules\PkgGapp\App\Enums;

enum MetadataGroupEnum: string
{
    case GENERAL = 'general';         // Métadonnées générales
    case VALIDATION = 'validation';   // Métadonnées pour les règles de validation
    case DISPLAY = 'display';         // Métadonnées pour les propriétés d'affichage
    case SYSTEM = 'system';           // Métadonnées système réservées
    case PERMISSION = 'permission';   // Métadonnées liées aux permissions
    case RELATION = 'relation';       // Métadonnées décrivant des relations
    case CUSTOM = 'custom';           // Métadonnées personnalisées

    /**
     * Récupère toutes les valeurs de l'énumération.
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Vérifie si une valeur donnée est valide pour cette énumération.
     *
     * @param string $value
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        return in_array($value, self::values(), true);
    }
}
