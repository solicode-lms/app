<?php

namespace Modules\PkgGapp\Services;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\App\Traits\GappCommands;
use Modules\PkgGapp\Services\Base\BaseEMetadataDefinitionService;

/**
 * Classe EMetadataDefinitionService pour gérer la persistance de l'entité EMetadataDefinition.
 */
class EMetadataDefinitionService extends BaseEMetadataDefinitionService
{
    use GappCommands;
    
    public function dataCalcul($eMetadataDefinition)
    {
        // En Cas d'édit
        if(isset($eMetadataDefinition->id)){
          
        }
      
        return $eMetadataDefinition;
    }


    /**
     * Override de la méthode create
     */
    public function create($data)
    {
        $value = parent::create($data);
        $this->metaExport();
        return $value;
    }

    /**
     * Override de la méthode update
     */
    public function update($id, array $data): ?Model 
    {
        $value = parent::update($id, $data);
        $this->metaExport();
        return $value;
    }
   


}
