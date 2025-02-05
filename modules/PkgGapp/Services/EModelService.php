<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Services;
use Modules\PkgGapp\Services\Base\BaseEModelService;

/**
 * Classe EModelService pour gérer la persistance de l'entité EModel.
 */
class EModelService extends BaseEModelService
{
    public function dataCalcul($eModel)
    {
        // En Cas d'édit
        if(isset($eModel->id)){
          
        }
      
        return $eModel;
    }
}
