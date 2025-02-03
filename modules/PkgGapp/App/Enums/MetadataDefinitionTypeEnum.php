<?php

namespace Modules\PkgGapp\App\Enums;


enum MetadataDefinitionTypeEnum: string
{
    case STRING = 'string';       // Texte simple
    case INTEGER = 'integer';     // Nombre entier
    case FLOAT = 'float';         // Nombre décimal
    case BOOLEAN = 'boolean';     // Valeur booléenne (true/false)
    case DATE = 'date';           // Date simple (AAAA-MM-JJ)
    case DATETIME = 'datetime';   // Date et heure (AAAA-MM-JJ HH:mm:ss)
    case ENUM = 'enum';           // Enumération (valeurs définies)
    case JSON = 'json';           // Données structurées JSON
    case TEXT = 'text';           // Texte long (blob)
    case RELATION = 'relation';   // Référence à une autre entité

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
