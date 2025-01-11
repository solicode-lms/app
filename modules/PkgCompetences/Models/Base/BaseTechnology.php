<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgCompetences\Models\CategoryTechnology;
use Modules\PkgCompetences\Models\Competence;
use Modules\PkgCreationProjet\Models\TransfertCompetence;

/**
 * Classe BaseTechnology
 * Cette classe sert de base pour le modèle Technology.
 */
class BaseTechnology extends BaseModel
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
        'category_technology_id', 'description', 'nom'
    ];

    /**
     * Relation BelongsTo pour CategoryTechnology.
     *
     * @return BelongsTo
     */
    public function categoryTechnology(): BelongsTo
    {
        return $this->belongsTo(CategoryTechnology::class, 'category_technology_id', 'id');
    }

    /**
     * Relation ManyToMany pour Competences.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function competences()
    {
        return $this->belongsToMany(Competence::class, 'competence_technology');
    }
    /**
     * Relation ManyToMany pour TransfertCompetences.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function transfertCompetences()
    {
        return $this->belongsToMany(TransfertCompetence::class, 'technology_transfert_competence');
    }


    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nom;
    }
}
