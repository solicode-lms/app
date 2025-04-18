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
        // Colonne dynamique : displayOrder
        $sql = "SELECT em.value_integer
        FROM e_metadata em
        JOIN e_metadata_definitions emd ON em.e_metadata_definition_id = emd.id
        WHERE em.e_data_field_id = e_data_fields.id
        AND emd.reference = 'displayOrder'";
        static::addDynamicAttribute('displayOrder', $sql);
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'default_value', 'column_name', 'e_model_id', 'e_relationship_id', 'field_order', 'data_type', 'db_primaryKey', 'db_nullable', 'db_unique', 'calculable', 'calculable_sql', 'description'
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
