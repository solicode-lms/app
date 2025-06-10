<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgCompetences\Models\Competence;
use Modules\PkgCompetences\Models\NiveauDifficulte;
use Modules\PkgCreationProjet\Models\Projet;
use Modules\PkgCompetences\Models\Technology;
use Modules\PkgRealisationProjets\Models\Validation;

/**
 * Classe BaseTransfertCompetence
 * Cette classe sert de base pour le modèle TransfertCompetence.
 */
class BaseTransfertCompetence extends BaseModel
{
    use HasFactory, HasDynamicContext, OwnedByUser;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
        'competence',
        'niveauDifficulte',
        'projet'
    ];


    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  true;
        $this->ownerRelationPath = "projet.formateur.user";
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'competence_id', 'niveau_difficulte_id', 'note', 'projet_id', 'question'
    ];
    public $manyToMany = [
        'Technology' => ['relation' => 'technologies' , "foreign_key" => "technology_id" ]
    ];
    public $manyToOne = [
        'Competence' => [
            'model' => "Modules\\PkgCompetences\\Models\\Competence",
            'relation' => 'competences' , 
            "foreign_key" => "competence_id", 
            ],
        'NiveauDifficulte' => [
            'model' => "Modules\\PkgCompetences\\Models\\NiveauDifficulte",
            'relation' => 'niveauDifficultes' , 
            "foreign_key" => "niveau_difficulte_id", 
            ],
        'Projet' => [
            'model' => "Modules\\PkgCreationProjet\\Models\\Projet",
            'relation' => 'projets' , 
            "foreign_key" => "projet_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour Competence.
     *
     * @return BelongsTo
     */
    public function competence(): BelongsTo
    {
        return $this->belongsTo(Competence::class, 'competence_id', 'id');
    }
    /**
     * Relation BelongsTo pour NiveauDifficulte.
     *
     * @return BelongsTo
     */
    public function niveauDifficulte(): BelongsTo
    {
        return $this->belongsTo(NiveauDifficulte::class, 'niveau_difficulte_id', 'id');
    }
    /**
     * Relation BelongsTo pour Projet.
     *
     * @return BelongsTo
     */
    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class, 'projet_id', 'id');
    }

    /**
     * Relation ManyToMany pour Technologies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'technology_transfert_competence');
    }

    /**
     * Relation HasMany pour TransfertCompetences.
     *
     * @return HasMany
     */
    public function validations(): HasMany
    {
        return $this->hasMany(Validation::class, 'transfert_competence_id', 'id');
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
