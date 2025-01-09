<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgGapp\Models\MetadataType;

/**
 * Classe BaseMetadatum
 * Cette classe sert de base pour le modèle Metadatum.
 */
class BaseMetadatum extends BaseModel
{
    use HasFactory, HasDynamicContext;

    public function __construct() {
        parent::__construct(); 
        $this->isOwnedByUser =  false;
    }

    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'value_boolean', 'value_string', 'value_int', 'value_object', 'object_id', 'object_type', 'metadata_type_id'
    ];

    /**
     * Relation BelongsTo pour MetadataType.
     *
     * @return BelongsTo
     */
    public function metadataType(): BelongsTo
    {
        return $this->belongsTo(MetadataType::class, 'metadata_type_id', 'id');
    }



    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->id;
    }
}
