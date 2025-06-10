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

/**
 * Classe BaseERelationship
 * Cette classe sert de base pour le modèle ERelationship.
 */
class BaseERelationship extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
        'sourceEModel',
        'targetEModel'
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
        'name', 'type', 'source_e_model_id', 'target_e_model_id', 'cascade_on_delete', 'is_cascade', 'description', 'column_name', 'referenced_table', 'referenced_column', 'through', 'with_column', 'morph_name'
    ];
    public $manyToOne = [
        'EModel' => [
            'model' => "Modules\\PkgGapp\\Models\\EModel",
            'relation' => 'eModels' , 
            "foreign_key" => "e_model_id", 
            ],
        'EModel' => [
            'model' => "Modules\\PkgGapp\\Models\\EModel",
            'relation' => 'eModels' , 
            "foreign_key" => "e_model_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour EModel.
     *
     * @return BelongsTo
     */
    public function sourceEModel(): BelongsTo
    {
        return $this->belongsTo(EModel::class, 'source_e_model_id', 'id');
    }
    /**
     * Relation BelongsTo pour EModel.
     *
     * @return BelongsTo
     */
    public function targetEModel(): BelongsTo
    {
        return $this->belongsTo(EModel::class, 'target_e_model_id', 'id');
    }


    /**
     * Relation HasMany pour ERelationships.
     *
     * @return HasMany
     */
    public function eDataFields(): HasMany
    {
        return $this->hasMany(EDataField::class, 'e_relationship_id', 'id');
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
