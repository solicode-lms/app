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
use Modules\PkgCompetences\Models\MicroCompetence;
use Modules\PkgSessions\Models\AlignementUa;
use Modules\PkgCompetences\Models\Chapitre;
use Modules\PkgCompetences\Models\CritereEvaluation;
use Modules\PkgApprentissage\Models\RealisationUa;
use Modules\PkgCreationProjet\Models\MobilisationUa;

/**
 * Classe BaseUniteApprentissage
 * Cette classe sert de base pour le modèle UniteApprentissage.
 */
class BaseUniteApprentissage extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'microCompetence'
    ];


    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  false;
        // Colonne dynamique : nom_filiere
        $sql = "SELECT f.nom
        FROM filieres f
        JOIN modules m ON m.filiere_id = f.id
        JOIN competences c ON c.module_id = m.id
        JOIN micro_competences mc ON mc.competence_id = c.id
        WHERE mc.id = unite_apprentissages.micro_competence_id
        LIMIT 1";
        static::addDynamicAttribute('nom_filiere', $sql);
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'ordre', 'code', 'nom', 'micro_competence_id', 'lien', 'description'
    ];
    public $manyToOne = [
        'MicroCompetence' => [
            'model' => "Modules\\PkgCompetences\\Models\\MicroCompetence",
            'relation' => 'microCompetences' , 
            "foreign_key" => "micro_competence_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour MicroCompetence.
     *
     * @return BelongsTo
     */
    public function microCompetence(): BelongsTo
    {
        return $this->belongsTo(MicroCompetence::class, 'micro_competence_id', 'id');
    }


    /**
     * Relation HasMany pour UniteApprentissages.
     *
     * @return HasMany
     */
    public function alignementUas(): HasMany
    {
        return $this->hasMany(AlignementUa::class, 'unite_apprentissage_id', 'id');
    }
    /**
     * Relation HasMany pour UniteApprentissages.
     *
     * @return HasMany
     */
    public function chapitres(): HasMany
    {
        return $this->hasMany(Chapitre::class, 'unite_apprentissage_id', 'id');
    }
    /**
     * Relation HasMany pour UniteApprentissages.
     *
     * @return HasMany
     */
    public function critereEvaluations(): HasMany
    {
        return $this->hasMany(CritereEvaluation::class, 'unite_apprentissage_id', 'id');
    }
    /**
     * Relation HasMany pour UniteApprentissages.
     *
     * @return HasMany
     */
    public function realisationUas(): HasMany
    {
        return $this->hasMany(RealisationUa::class, 'unite_apprentissage_id', 'id');
    }
    /**
     * Relation HasMany pour UniteApprentissages.
     *
     * @return HasMany
     */
    public function mobilisationUas(): HasMany
    {
        return $this->hasMany(MobilisationUa::class, 'unite_apprentissage_id', 'id');
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
