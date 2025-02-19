<?php
// generateReference


namespace Modules\PkgGapp\Models;
use Modules\PkgGapp\Models\Base\BaseEMetadataDefinition;

class EMetadataDefinition extends BaseEMetadataDefinition
{
    public function generateReference(): string
    {
        return $this->name;
    }
}
