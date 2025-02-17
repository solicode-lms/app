<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Services;
use Modules\PkgGapp\Services\Base\BaseERelationshipService;

/**
 * Classe ERelationshipService pour gérer la persistance de l'entité ERelationship.
 */
class ERelationshipService extends BaseERelationshipService
{
    public function dataCalcul($eRelationship)
    {
        // En Cas d'édit
        if(isset($eRelationship->id)){
          
        }
      
        return $eRelationship;
    }
   
}
