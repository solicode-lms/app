<?php


namespace Modules\PkgGapp\Models;
use Modules\PkgGapp\Models\Base\BaseEDataField;

class EDataField extends BaseEDataField
{

    public function getOrder(){

        return $this->eMetadata()
        ->whereHas('eMetadataDefinition', function ($query) {
            $query->where('reference', 'displayOrder');
        })
        ->value('value_integer');
    }

}
