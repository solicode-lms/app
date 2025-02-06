<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Services;
use Modules\PkgRealisationProjets\Services\Base\BaseValidationService;

/**
 * Classe ValidationService pour gérer la persistance de l'entité Validation.
 */
class ValidationService extends BaseValidationService
{
    public function dataCalcul($validation)
    {
        // En Cas d'édit
        if(isset($validation->id)){
          
        }
      
        return $validation;
    }
}
