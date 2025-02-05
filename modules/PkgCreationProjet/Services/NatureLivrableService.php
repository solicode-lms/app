<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Services;
use Modules\PkgCreationProjet\Services\Base\BaseNatureLivrableService;

/**
 * Classe NatureLivrableService pour gérer la persistance de l'entité NatureLivrable.
 */
class NatureLivrableService extends BaseNatureLivrableService
{
    public function dataCalcul($natureLivrable)
    {
        // En Cas d'édit
        if(isset($natureLivrable->id)){
          
        }
      
        return $natureLivrable;
    }
}
