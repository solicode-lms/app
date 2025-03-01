<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Services;
use Modules\PkgGestionTaches\Services\Base\BaseEtatRealisationTacheService;

/**
 * Classe EtatRealisationTacheService pour gérer la persistance de l'entité EtatRealisationTache.
 */
class EtatRealisationTacheService extends BaseEtatRealisationTacheService
{
    public function dataCalcul($etatRealisationTache)
    {
        // En Cas d'édit
        if(isset($etatRealisationTache->id)){
          
        }
      
        return $etatRealisationTache;
    }
   
}
