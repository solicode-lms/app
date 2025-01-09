<?php
 

namespace Modules\PkgGapp\Models;

use Modules\PkgGapp\App\Enums\MetadataScope;
use Modules\PkgGapp\App\Enums\MetaDataValueType;
use Modules\PkgGapp\Models\Base\BaseMetadataType;

class MetadataType extends BaseMetadataType
{

    /**
     * Casts pour les attributs.
     *
     * @var array
     */
    protected $casts = [
        'is_required' => 'boolean',
        'type' => MetaDataValueType::class,
        'scope' => MetadataScope::class,
    ];


}
