<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Services;
use Modules\Core\Services\Base\BaseFeatureDomainService;

/**
 * Classe FeatureDomainService pour gérer la persistance de l'entité FeatureDomain.
 */
class FeatureDomainService extends BaseFeatureDomainService
{
    public function dataCalcul($featureDomain)
    {
        // En Cas d'édit
        if(isset($featureDomain->id)){
          
        }
      
        return $featureDomain;
    }
}
