<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Services;
use Modules\PkgGestionTaches\Services\Base\BaseLabelRealisationTacheService;

/**
 * Classe LabelRealisationTacheService pour gérer la persistance de l'entité LabelRealisationTache.
 */
class LabelRealisationTacheService extends BaseLabelRealisationTacheService
{
    public function dataCalcul($labelRealisationTache)
    {
        // En Cas d'édit
        if(isset($labelRealisationTache->id)){
          
        }
      
        return $labelRealisationTache;
    }
   
}
