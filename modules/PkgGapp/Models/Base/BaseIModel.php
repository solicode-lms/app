<?php


namespace Modules\PkgGapp\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgGapp\Models\DataField;
use Modules\PkgGapp\Models\IPackage;
use Modules\PkgGapp\Models\Relationship;

/**
 * Classe BaseIModel
 * Cette classe sert de base pour le modèle IModel.
 */
class BaseIModel extends BaseModel
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
        'name', 'icon', 'description', 'i_package_id'
    ];

    /**
     * Relation BelongsTo pour IPackage.
     *
     * @return BelongsTo
     */
    public function iPackage(): BelongsTo
    {
        return $this->belongsTo(IPackage::class, 'i_package_id', 'id');
    }


    /**
     * Relation HasMany pour DataFields.
     *
     * @return HasMany
     */
    public function dataFields(): HasMany
    {
        return $this->hasMany(DataField::class, 'imodel_id', 'id');
    }
    /**
     * Relation HasMany pour Relationships.
     *
     * @return HasMany
     */
    public function relationships(): HasMany
    {
        return $this->hasMany(Relationship::class, 'imodel_id', 'id');
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
