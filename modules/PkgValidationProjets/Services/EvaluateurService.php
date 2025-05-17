<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgValidationProjets\Services;
use Modules\PkgValidationProjets\Services\Base\BaseEvaluateurService;

/**
 * Classe EvaluateurService pour gérer la persistance de l'entité Evaluateur.
 */
class EvaluateurService extends BaseEvaluateurService
{
    public function dataCalcul($evaluateur)
    {
        // En Cas d'édit
        if(isset($evaluateur->id)){
          
        }
      
        return $evaluateur;
    }
   
}
