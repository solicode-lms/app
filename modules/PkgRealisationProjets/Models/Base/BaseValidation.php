<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgCreationProjet\Models\TransfertCompetence;

/**
 * Classe BaseValidation
 * Cette classe sert de base pour le modèle Validation.
 */
class BaseValidation extends BaseModel
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
        'note', 'message', 'is_valide', 'transfert_competence_id', 'realisation_projet_id'
    ];

    /**
     * Relation BelongsTo pour RealisationProjet.
     *
     * @return BelongsTo
     */
    public function realisationProjet(): BelongsTo
    {
        return $this->belongsTo(RealisationProjet::class, 'realisation_projet_id', 'id');
    }
    /**
     * Relation BelongsTo pour TransfertCompetence.
     *
     * @return BelongsTo
     */
    public function transfertCompetence(): BelongsTo
    {
        return $this->belongsTo(TransfertCompetence::class, 'transfert_competence_id', 'id');
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
