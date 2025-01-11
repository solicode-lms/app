<?php


namespace Modules\PkgGapp\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgGapp\Models\EDataField;
use Modules\PkgGapp\Models\EPackage;
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
        'name', 'icon', 'description', 'e_package_id'
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
     * Relation HasMany pour EDataFields.
     *
     * @return HasMany
     */
    public function eDataFields(): HasMany
    {
        return $this->hasMany(EDataField::class, 'emodel_id', 'id');
    }
    /**
     * Relation HasMany pour ERelationships.
     *
     * @return HasMany
     */
    
    /**
     * Relation HasMany pour ERelationships.
     *
     * @return HasMany
     */
    public function eRelationships(): HasMany
    {
        return $this->hasMany(ERelationship::class, 'emodel_id', 'id');
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
