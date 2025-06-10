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
use Modules\PkgAutoformation\Models\Formation;
use Modules\PkgCompetences\Models\NiveauCompetence;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgAutoformation\Models\Chapitre;
use Modules\PkgAutoformation\Models\RealisationChapitre;

/**
 * Classe BaseChapitre
 * Cette classe sert de base pour le modèle Chapitre.
 */
class BaseChapitre extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
        'formation',
        'niveauCompetence',
        'formateur',
        'chapitreOfficiel'
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
        'nom', 'lien', 'coefficient', 'description', 'ordre', 'is_officiel', 'formation_id', 'niveau_competence_id', 'formateur_id', 'chapitre_officiel_id'
    ];
    public $manyToOne = [
        'Formation' => [
            'model' => "Modules\\PkgAutoformation\\Models\\Formation",
            'relation' => 'formations' , 
            "foreign_key" => "formation_id", 
            ],
        'NiveauCompetence' => [
            'model' => "Modules\\PkgCompetences\\Models\\NiveauCompetence",
            'relation' => 'niveauCompetences' , 
            "foreign_key" => "niveau_competence_id", 
            ],
        'Formateur' => [
            'model' => "Modules\\PkgFormation\\Models\\Formateur",
            'relation' => 'formateurs' , 
            "foreign_key" => "formateur_id", 
            ],
        'Chapitre' => [
            'model' => "Modules\\PkgAutoformation\\Models\\Chapitre",
            'relation' => 'chapitres' , 
            "foreign_key" => "chapitre_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour Formation.
     *
     * @return BelongsTo
     */
    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class, 'formation_id', 'id');
    }
    /**
     * Relation BelongsTo pour NiveauCompetence.
     *
     * @return BelongsTo
     */
    public function niveauCompetence(): BelongsTo
    {
        return $this->belongsTo(NiveauCompetence::class, 'niveau_competence_id', 'id');
    }
    /**
     * Relation BelongsTo pour Formateur.
     *
     * @return BelongsTo
     */
    public function formateur(): BelongsTo
    {
        return $this->belongsTo(Formateur::class, 'formateur_id', 'id');
    }
    /**
     * Relation BelongsTo pour Chapitre.
     *
     * @return BelongsTo
     */
    public function chapitreOfficiel(): BelongsTo
    {
        return $this->belongsTo(Chapitre::class, 'chapitre_officiel_id', 'id');
    }


    /**
     * Relation HasMany pour Chapitres.
     *
     * @return HasMany
     */
    public function chapitreOfficielIdChapitres(): HasMany
    {
        return $this->hasMany(Chapitre::class, 'chapitre_officiel_id', 'id');
    }
    /**
     * Relation HasMany pour Chapitres.
     *
     * @return HasMany
     */
    public function realisationChapitres(): HasMany
    {
        return $this->hasMany(RealisationChapitre::class, 'chapitre_id', 'id');
    }



    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nom ?? "";
    }
}
