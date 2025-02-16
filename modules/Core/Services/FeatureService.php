<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Services;
use Modules\Core\Services\Base\BaseFeatureService;

/**
 * Classe FeatureService pour gérer la persistance de l'entité Feature.
 */
class FeatureService extends BaseFeatureService
{
    public function dataCalcul($feature)
    {
        // En Cas d'édit
        if(isset($feature->id)){
          
        }
      
        return $feature;
    }
   
}
