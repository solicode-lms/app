<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutoformation\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgAutoformation\Models\EtatFormation;

/**
 * Classe BaseWorkflowFormation
 * Cette classe sert de base pour le modèle WorkflowFormation.
 */
class BaseWorkflowFormation extends BaseModel
{
    use HasFactory, HasDynamicContext;

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
        'code', 'titre', 'description'
    ];




    /**
     * Relation HasMany pour WorkflowFormations.
     *
     * @return HasMany
     */
    public function etatFormations(): HasMany
    {
        return $this->hasMany(EtatFormation::class, 'workflow_formation_id', 'id');
    }



    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->code ?? "";
    }
}
