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
use Modules\PkgGapp\Models\FieldType;
use Modules\PkgGapp\Models\IModel;

/**
 * Classe BaseDataField
 * Cette classe sert de base pour le modèle DataField.
 */
class BaseDataField extends BaseModel
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
        'name', 'i_model_id', 'field_type_id', 'description'
    ];

    /**
     * Relation BelongsTo pour FieldType.
     *
     * @return BelongsTo
     */
    public function fieldType(): BelongsTo
    {
        return $this->belongsTo(FieldType::class, 'field_type_id', 'id');
    }
    /**
     * Relation BelongsTo pour IModel.
     *
     * @return BelongsTo
     */
    public function iModel(): BelongsTo
    {
        return $this->belongsTo(IModel::class, 'i_model_id', 'id');
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
