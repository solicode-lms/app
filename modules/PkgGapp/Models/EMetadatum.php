<?php


namespace Modules\PkgGapp\Models;
use Modules\PkgGapp\Models\Base\BaseEMetadatum;

class EMetadatum extends BaseEMetadatum
{
    // TODO : ajouter à GApp
    public function object()
    {
        return $this->morphTo();
    }

    public function generateReference(): string
    {
        return $this->object->reference . "_" . $this->eMetadataDefinition->reference ;
    }

}
