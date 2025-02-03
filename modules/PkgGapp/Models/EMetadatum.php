<?php


namespace Modules\PkgGapp\Models;
use Modules\PkgGapp\Models\Base\BaseEMetadatum;

class EMetadatum extends BaseEMetadatum
{


    public function generateReference(): string
    {
        $objet_reference = "";
        if($this->eDataField != null) {
            $objet_reference = $this->eDataField->reference;
        }
        if($this->eModel != null) {
            $objet_reference = $this->eModel->reference;
        }

        return $objet_reference . "_" . $this->eMetadataDefinition->reference ;
    }

}
