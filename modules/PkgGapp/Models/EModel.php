<?php


namespace Modules\PkgGapp\Models;
use Modules\PkgGapp\Models\Base\BaseEModel;

class EModel extends BaseEModel
{

     protected $with = [
       'ePackage'
    ];

    public function getIcone()
    {
        // Recherche de la metadata qui correspond à "IconModel"
        $iconMetadata = $this->eMetadata()
            ->whereHas('eMetadataDefinition', function ($query) {
                $query->where('reference', 'iconModel');
            })
            ->first();
    
        // Retourne la valeur de l'icône si elle existe, sinon une valeur par défaut
        return $iconMetadata ? $iconMetadata->value_string : 'fa-table';
    }
}
