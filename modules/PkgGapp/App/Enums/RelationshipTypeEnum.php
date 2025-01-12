<?php
namespace Modules\PkgGapp\App\Enums;

enum RelationshipTypeEnum: string
{
    case ONE_TO_ONE = 'OneToOne';       // Relation 1 à 1
    case ONE_TO_MANY = 'OneToMany';     // Relation 1 à n
    case MANY_TO_ONE = 'ManyToOne';     // Relation n à 1
    case MANY_TO_MANY = 'ManyToMany';   // Relation n à n
    case SELF = 'Self';                   // Relation sur elle-même
    case AGGREGATION = 'Aggregation';     // Agrégation
    case COMPOSITION = 'Composition';     // Composition forte

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
