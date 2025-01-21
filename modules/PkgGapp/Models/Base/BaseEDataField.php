<?php


namespace Modules\PkgGapp\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Core\Models\BaseModel;
use Modules\PkgGapp\Models\EMetadatum;
use Modules\PkgGapp\Models\EModel;

/**
 * Classe BaseEDataField
 * Cette classe sert de base pour le modèle EDataField.
 */
class BaseEDataField extends BaseModel
{
    use HasFactory, HasDynamicContext;

    public function __construct() {
        parent::__construct(); 
        $this->isOwnedByUser =  false;
    }

    public function eMetadata(): MorphMany
    {
        return $this->morphMany(EMetadatum::class, 'object');
    }
    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'column_name', 'data_type', 'db_nullable', 'db_primaryKey', 'db_unique', 'default_value', 'description', 'e_model_id'
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
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->code;
    }
}
