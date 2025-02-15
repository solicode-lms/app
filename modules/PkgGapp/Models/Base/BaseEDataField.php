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
use Modules\PkgGapp\Models\EModel;
use Modules\PkgGapp\Models\ERelationship;
use Modules\PkgGapp\Models\EMetadatum;

/**
 * Classe BaseEDataField
 * Cette classe sert de base pour le modèle EDataField.
 */
class BaseEDataField extends BaseModel
{
    use HasFactory, HasDynamicContext;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  false;
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'order', 'name', 'column_name', 'field_order', 'db_nullable', 'db_primaryKey', 'db_unique', 'default_value', 'description', 'e_model_id', 'e_relationship_id', 'data_type'
    ];

    /**
     * Relation BelongsTo pour EModel.
     *
     * @return BelongsTo
     */
    public function eModel(): BelongsTo
    {
        return $this->belongsTo(EModel::class, 'e_model_id', 'id');
    }
    /**
     * Relation BelongsTo pour ERelationship.
     *
     * @return BelongsTo
     */
    public function eRelationship(): BelongsTo
    {
        return $this->belongsTo(ERelationship::class, 'e_relationship_id', 'id');
    }


    /**
     * Relation HasMany pour EDataFields.
     *
     * @return HasMany
     */
    public function eMetadata(): HasMany
    {
        return $this->hasMany(EMetadatum::class, 'e_data_field_id', 'id');
    }



    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name ?? "";
    }
}
