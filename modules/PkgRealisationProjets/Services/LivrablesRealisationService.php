<?php
 

namespace Modules\PkgRealisationProjets\Services;
use Modules\PkgRealisationProjets\Services\Base\BaseLivrablesRealisationService;

/**
 * Classe LivrablesRealisationService pour gérer la persistance de l'entité LivrablesRealisation.
 */
class LivrablesRealisationService extends BaseLivrablesRealisationService
{

     protected array $index_with_relations = ['realisationProjet'];


    public function dataCalcul($livrablesRealisation)
    {
        // En Cas d'édit
        if(isset($livrablesRealisation->id)){
          
        }
      
        return $livrablesRealisation;
    }
   
}
