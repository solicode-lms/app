<?php
namespace Modules\PkgGapp\App\Enums;

enum MetadataScopeEnum: string
{
    case MODULE = 'module'; // Métadonnée spécifique à un module
    case FIELD = 'field';   // Métadonnée liée à un champ ou une propriété spécifique

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
