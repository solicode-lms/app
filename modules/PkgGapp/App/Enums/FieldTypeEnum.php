<?php

namespace Modules\PkgGapp\App\Enums;


enum FieldTypeEnum: string
{
    case STRING = 'String';       // Texte simple
    case INTEGER = 'Integer';     // Nombre entier
    case FLOAT = 'Float';         // Nombre décimal
    case BOOLEAN = 'Boolean';     // Valeur booléenne (true/false)
    case DATE = 'Date';           // Date simple (AAAA-MM-JJ)
    case DATETIME = 'Datetime';   // Date et heure (AAAA-MM-JJ HH:mm:ss)
    case ENUM = 'Enum';           // Enumération (valeurs définies)
    case JSON = 'Json';           // Données structurées JSON
    case TEXT = 'Text';           // Texte long (blob)
    case RELATION = 'Relation';   // Référence à une autre entité

    /**
     * Récupère toutes les valeurs de l'énumération.
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
