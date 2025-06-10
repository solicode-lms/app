<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\Core\Models\FeatureDomain;
use Modules\PkgAutorisation\Models\Permission;

/**
 * Classe BaseFeature
 * Cette classe sert de base pour le modèle Feature.
 */
class BaseFeature extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
        'featureDomain'
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
        'name', 'description', 'feature_domain_id'
    ];
    public $manyToMany = [
        'Permission' => ['relation' => 'permissions' , "foreign_key" => "permission_id" ]
    ];
    public $manyToOne = [
        'FeatureDomain' => [
            'model' => "Modules\\Core\\Models\\FeatureDomain",
            'relation' => 'featureDomains' , 
            "foreign_key" => "feature_domain_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour FeatureDomain.
     *
     * @return BelongsTo
     */
    public function featureDomain(): BelongsTo
    {
        return $this->belongsTo(FeatureDomain::class, 'feature_domain_id', 'id');
    }

    /**
     * Relation ManyToMany pour Permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'feature_permission');
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
