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
use Modules\PkgGapp\Models\EMetadataDefinition;

/**
 * Classe BaseEMetadatum
 * Cette classe sert de base pour le modèle EMetadatum.
 */
class BaseEMetadatum extends BaseModel
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
        'e_metadata_definition_id', 'object_id', 'object_type', 'value_boolean', 'value_int', 'value_object', 'value_string'
    ];

    /**
     * Relation BelongsTo pour EMetadataDefinition.
     *
     * @return BelongsTo
     */
    public function eMetadataDefinition(): BelongsTo
    {
        return $this->belongsTo(EMetadataDefinition::class, 'e_metadata_definition_id', 'id');
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
