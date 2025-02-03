<?php


namespace Modules\PkgGapp\Models;

use Modules\PkgGapp\App\Enums\RelationshipType;
use Modules\PkgGapp\Models\Base\BaseRelationship;

class Relationship extends BaseRelationship
{
   /**
     * Casts des attributs.
     */
    protected $casts = [
        'type' => RelationshipType::class, // Cast automatique pour RelationshipType
    ];

        /**
     * Relation polymorphe pour les métadonnées associées.
     */
    public function metadata()
    {
        return $this->morphMany(Metadatum::class, 'object');
    }
}
