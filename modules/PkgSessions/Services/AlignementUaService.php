<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgSessions\Services;
use Modules\PkgSessions\Services\Base\BaseAlignementUaService;

/**
 * Classe AlignementUaService pour gérer la persistance de l'entité AlignementUa.
 */
class AlignementUaService extends BaseAlignementUaService
{
    public function dataCalcul($alignementUa)
    {
        // En Cas d'édit
        if(isset($alignementUa->id)){
          
        }
      
        return $alignementUa;
    }
   
}
