<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Models;
use Modules\PkgGapp\Models\Base\BaseIModel;

class IModel extends BaseIModel
{

    /**
     * Relation polymorphe pour les métadonnées associées.
     */
    public function metadata()
    {
        return $this->morphMany(Metadatum::class, 'object');
    }

}
