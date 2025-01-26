<?php
// generateReference


namespace Modules\PkgGapp\Models;
use Modules\PkgGapp\Models\Base\BaseEMetadataDefinition;

class EMetadataDefinition extends BaseEMetadataDefinition
{

    // TODO : add Metadata : utiliser par défaut les chmps unique, si non, utiliser 
    // ToString un référence avec tous les field
    public function generateReference(): string
    {
        return $this->name;
    }
}
