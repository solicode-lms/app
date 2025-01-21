<?php


namespace Modules\PkgGapp\Models;
use Modules\PkgGapp\Models\Base\BaseEMetadatum;

class EMetadatum extends BaseEMetadatum
{
    public function object()
    {
        return $this->morphTo();
    }

}
