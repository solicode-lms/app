<?php


namespace Modules\PkgGapp\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgGapp\Models\IModel;

/**
 * Classe BaseRelationship
 * Cette classe sert de base pour le modèle Relationship.
 */
class BaseRelationship extends BaseModel
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
        'source_e_model_id', 'target_e_model_id', 'type', 'source_field', 'target_field', 'cascade_on_delete', 'description'
    ];

    /**
     * Relation BelongsTo pour IModel.
     *
     * @return BelongsTo
     */
    public function iModel(): BelongsTo
    {
        return $this->belongsTo(IModel::class, 'target_e_model_id', 'id');
    }



    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->type;
    }
}
