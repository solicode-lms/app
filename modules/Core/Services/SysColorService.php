<?php


namespace Modules\Core\Services;
use Modules\Core\Services\Base\BaseSysColorService;

/**
 * Classe SysColorService pour gérer la persistance de l'entité SysColor.
 */
class SysColorService extends BaseSysColorService
{
 
    function getTextColorForBackground($hexColor): string
    {
        // Supprimer le "#" si présent
        $hexColor = ltrim($hexColor, '#');

        // Convertir en RGB
        $r = hexdec(substr($hexColor, 0, 2));
        $g = hexdec(substr($hexColor, 2, 2));
        $b = hexdec(substr($hexColor, 4, 2));

        // Calcul de la luminance relative (selon le standard W3C)
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b);

        // Seuil de contraste : généralement 128
        return $luminance > 128 ? 'black' : 'white';
    }
   
}
