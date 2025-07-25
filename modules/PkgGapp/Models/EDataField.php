<?php


namespace Modules\PkgGapp\Models;
use Modules\PkgGapp\Models\Base\BaseEDataField;

class EDataField extends BaseEDataField
{

    protected bool $allowReferenceUpdate = false;
    
    protected $with = [
       'eModel',
       'eRelationship'
    ];

    public function getOrder(){

        return $this->eMetadata()
        ->whereHas('eMetadataDefinition', function ($query) {
            $query->where('reference', 'displayOrder');
        })
        ->value('value_integer');
    }

    public function generateReference(): string
    {
        return $this->eModel->name . "_" .  $this->name;
    }

}
