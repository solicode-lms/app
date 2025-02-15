<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\PkgGapp\Models\Base\BaseERelationship;

class ERelationship extends BaseERelationship
{

    public function sourceEModel(): BelongsTo
    {
        return $this->belongsTo(EModel::class, 'source_e_model_id', 'id');
    }


    public function targerEModel(): BelongsTo
    {
        return $this->belongsTo(EModel::class, 'target_e_model_id', 'id');
    }


    public function __toString()
    {
        return $this->sourceEModel . "-" .  $this->targerEModel . "-" .  $this->type;
    }

}
