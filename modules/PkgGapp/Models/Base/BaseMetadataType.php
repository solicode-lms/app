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
use Modules\PkgGapp\Models\Metadatum;

/**
 * Classe BaseMetadataType
 * Cette classe sert de base pour le modèle MetadataType.
 */
class BaseMetadataType extends BaseModel
{
    use HasFactory, HasDynamicContext;

    public function __construct() {
        parent::__construct(); 
        $this->isOwnedByUser =  false;
    }

    /**
    * Les castes des attributs pour le modèle.
    *
    * @var array
    */
    protected $casts = [
        'type' => \Modules\PkgGapp\App\Enums\MetaDataValueType::class,
        'scope' => \Modules\PkgGapp\App\Enums\MetadataScope::class,
    ];
    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'type', 'scope', 'description', 'default_value', 'validation_rules'
    ];



    /**
     * Relation HasMany pour Metadata.
     *
     * @return HasMany
     */
    public function metadata(): HasMany
    {
        return $this->hasMany(Metadatum::class, 'metadata_type_id', 'id');
    }

    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
