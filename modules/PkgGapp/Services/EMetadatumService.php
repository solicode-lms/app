<?php

namespace Modules\PkgGapp\Services;
use Modules\PkgGapp\Services\Base\BaseEMetadatumService;

/**
 * Classe EMetadatumService pour gérer la persistance de l'entité EMetadatum.
 */
class EMetadatumService extends BaseEMetadatumService
{
    public function create(array $data)
    {
        // $objet = $data->object;
        // $data->code = $objet.name . 
        return parent::create($data);
    }
    // public function createInstance()
    // {
    //     $objet = parent::createInstance();



    //     // $data->code = $objet.name . 
    //     return $objet;
    // }

 
   
}
