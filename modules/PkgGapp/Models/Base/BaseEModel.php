<?php
// Deux relation HasMany : avec même table


namespace Modules\PkgGapp\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgGapp\Models\EPackage;
use Modules\PkgGapp\Models\EDataField;
use Modules\PkgGapp\Models\EMetadatum;
use Modules\PkgGapp\Models\ERelationship;

/**
 * Classe BaseEModel
 * Cette classe sert de base pour le modèle EModel.
 */
class BaseEModel extends BaseModel
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
        'name', 'table_name', 'icon', 'is_pivot_table', 'description', 'e_package_id'
    ];

    /**
     * Relation BelongsTo pour EPackage.
     *
     * @return BelongsTo
     */
    public function ePackage(): BelongsTo
    {
        return $this->belongsTo(EPackage::class, 'e_package_id', 'id');
    }


    /**
     * Relation HasMany pour EModels.
     *
     * @return HasMany
     */
    public function eDataFields(): HasMany
    {
        return $this->hasMany(EDataField::class, 'e_model_id', 'id');
    }
    /**
     * Relation HasMany pour EModels.
     *
     * @return HasMany
     */
    public function eMetadata(): HasMany
    {
        return $this->hasMany(EMetadatum::class, 'e_model_id', 'id');
    }
    /**
     * Relation HasMany pour EModels.
     *
     * @return HasMany
     */
    public function sourceRelationships(): HasMany
    {
        return $this->hasMany(ERelationship::class, 'source_e_model_id', 'id');
    }
    /**
     * Relation HasMany pour EModels.
     *
     * @return HasMany
     */
    public function targetRelationships(): HasMany
    {
        return $this->hasMany(ERelationship::class, 'target_e_model_id', 'id');
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
