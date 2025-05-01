<?php

namespace Modules\Core\Utils;

use DateTimeInterface;

/**
 * Classe utilitaire pour la gestion des dates.
 */
class DateUtil
{
    /**
     * Vérifie si la valeur est une date ou un datetime valide.
     *
     * @param mixed $valeur
     * @return bool
     */
    public static function estDateOuDateTime($valeur): bool
    {
        return $valeur instanceof DateTimeInterface || (is_string($valeur) && strtotime($valeur) !== false);
    }

    /**
     * Formate une valeur de type date/datetime en string standard 'Y-m-d H:i:s'.
     *
     * @param mixed $valeur
     * @return string|null
     */
    public static function formatterDate($valeur): ?string
    {
        if ($valeur instanceof DateTimeInterface) {
            return $valeur->format('Y-m-d H:i:s');
        }

        if (is_string($valeur) && strtotime($valeur) !== false) {
            return date('Y-m-d H:i:s', strtotime($valeur));
        }

        return null;
    }
}
