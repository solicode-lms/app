<?php


namespace Modules\PkgGapp\Models;
use Modules\PkgGapp\Models\Base\BaseEDataField;

class EDataField extends BaseEDataField
{
    public function eMetadata()
    {
        return $this->morphMany(EMetadatum::class, 'object');
    }
}
