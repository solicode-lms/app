<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Services;
use Modules\PkgGestionTaches\Services\Base\BaseHistoriqueRealisationTacheService;

/**
 * Classe HistoriqueRealisationTacheService pour gérer la persistance de l'entité HistoriqueRealisationTache.
 */
class HistoriqueRealisationTacheService extends BaseHistoriqueRealisationTacheService
{
    public function dataCalcul($historiqueRealisationTache)
    {
        // En Cas d'édit
        if(isset($historiqueRealisationTache->id)){
          
        }
      
        return $historiqueRealisationTache;
    }
   
}
