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
use Modules\PkgGapp\Models\EDataField;
use Modules\PkgGapp\Models\EMetadataDefinition;

/**
 * Classe BaseEMetadatum
 * Cette classe sert de base pour le modèle EMetadatum.
 */
class BaseEMetadatum extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'eModel',
      //  'eDataField',
      //  'eMetadataDefinition'
    ];


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
        'value_boolean', 'value_string', 'value_integer', 'value_float', 'value_date', 'value_datetime', 'value_enum', 'value_json', 'value_text', 'e_model_id', 'e_data_field_id', 'e_metadata_definition_id'
    ];
    public $manyToOne = [
        'EModel' => [
            'model' => "Modules\\PkgGapp\\Models\\EModel",
            'relation' => 'eModels' , 
            "foreign_key" => "e_model_id", 
            ],
        'EDataField' => [
            'model' => "Modules\\PkgGapp\\Models\\EDataField",
            'relation' => 'eDataFields' , 
            "foreign_key" => "e_data_field_id", 
            ],
        'EMetadataDefinition' => [
            'model' => "Modules\\PkgGapp\\Models\\EMetadataDefinition",
            'relation' => 'eMetadataDefinitions' , 
            "foreign_key" => "e_metadata_definition_id", 
            ]
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
     * Relation BelongsTo pour EDataField.
     *
     * @return BelongsTo
     */
    public function eDataField(): BelongsTo
    {
        return $this->belongsTo(EDataField::class, 'e_data_field_id', 'id');
    }
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
        return $this->id ?? "";
    }
}
