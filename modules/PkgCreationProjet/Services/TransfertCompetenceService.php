<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Services;
use Modules\PkgCreationProjet\Services\Base\BaseTransfertCompetenceService;

/**
 * Classe TransfertCompetenceService pour gérer la persistance de l'entité TransfertCompetence.
 */
class TransfertCompetenceService extends BaseTransfertCompetenceService
{
    public function dataCalcul($transfertCompetence)
    {
        // En Cas d'édit
        if(isset($transfertCompetence->id)){
          
        }
      
        return $transfertCompetence;
    }
}
