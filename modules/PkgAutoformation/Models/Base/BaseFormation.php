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
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgAutoformation\Models\Formation;
use Modules\PkgCompetences\Models\Competence;
use Modules\PkgCompetences\Models\Technology;
use Modules\PkgAutoformation\Models\Chapitre;
use Modules\PkgAutoformation\Models\RealisationFormation;

/**
 * Classe BaseFormation
 * Cette classe sert de base pour le modèle Formation.
 */
class BaseFormation extends BaseModel
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
        'nom', 'lien', 'description', 'is_officiel', 'formateur_id', 'formation_officiel_id', 'competence_id'
    ];
    public $manyToMany = [
        'Technology' => ['relation' => 'technologies' , "foreign_key" => "technology_id" ]
    ];


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
     * Relation BelongsTo pour Formation.
     *
     * @return BelongsTo
     */
    public function formationOfficiel(): BelongsTo
    {
        return $this->belongsTo(Formation::class, 'formation_officiel_id', 'id');
    }
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
     * Relation ManyToMany pour Technologies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'formation_technology');
    }

    /**
     * Relation HasMany pour Formations.
     *
     * @return HasMany
     */
    public function chapitres(): HasMany
    {
        return $this->hasMany(Chapitre::class, 'formation_id', 'id');
    }
    /**
     * Relation HasMany pour Formations.
     *
     * @return HasMany
     */
    public function formationOfficielIdFormations(): HasMany
    {
        return $this->hasMany(Formation::class, 'formation_officiel_id', 'id');
    }
    /**
     * Relation HasMany pour Formations.
     *
     * @return HasMany
     */
    public function realisationFormations(): HasMany
    {
        return $this->hasMany(RealisationFormation::class, 'formation_id', 'id');
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
