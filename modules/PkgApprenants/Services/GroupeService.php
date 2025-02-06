<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Services;
use Modules\PkgApprenants\Services\Base\BaseGroupeService;

/**
 * Classe GroupeService pour gérer la persistance de l'entité Groupe.
 */
class GroupeService extends BaseGroupeService
{
    public function dataCalcul($groupe)
    {
        // En Cas d'édit
        if(isset($groupe->id)){
          
        }
      
        return $groupe;
    }
}
