<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Services;
use Modules\PkgRealisationProjets\Services\Base\BaseLivrablesRealisationService;

/**
 * Classe LivrablesRealisationService pour gérer la persistance de l'entité LivrablesRealisation.
 */
class LivrablesRealisationService extends BaseLivrablesRealisationService
{
    public function dataCalcul($livrablesRealisation)
    {
        // En Cas d'édit
        if(isset($livrablesRealisation->id)){
          
        }
      
        return $livrablesRealisation;
    }
   
}
