<?php

namespace Modules\PkgGapp\Models;

use Modules\PkgGapp\App\Enums\MetadataTargetType;
use Modules\PkgGapp\Models\Base\BaseMetadatum;

class Metadatum extends BaseMetadatum
{
    /**
     * Casts des attributs.
     */
    protected $casts = [
        'object_type' => MetadataTargetType::class, // Cast automatique pour MetadataTargetType
    ];

    /**
     * Relation polymorphe avec l'objet lié.
     */
    public function object()
    {
        return $this->morphTo();
    }
}
