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
use Modules\PkgCompetences\Models\Competence;
use Modules\PkgCompetences\Models\UniteApprentissage;

/**
 * Classe BaseMicroCompetence
 * Cette classe sert de base pour le modèle MicroCompetence.
 */
class BaseMicroCompetence extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'competence'
    ];


    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  false;
        // Colonne dynamique : filiere
        $sql = "SELECT f.nom
        FROM micro_competences mc
        JOIN competences c ON mc.competence_id = c.id
        JOIN modules m ON c.module_id = m.id
        JOIN filieres f ON m.filiere_id = f.id
        WHERE mc.id = micro_competences.id";
        static::addDynamicAttribute('filiere', $sql);
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'ordre', 'code', 'titre', 'sous_titre', 'competence_id', 'lien', 'description'
    ];
    public $manyToOne = [
        'Competence' => [
            'model' => "Modules\\PkgCompetences\\Models\\Competence",
            'relation' => 'competences' , 
            "foreign_key" => "competence_id", 
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
     * Relation HasMany pour MicroCompetences.
     *
     * @return HasMany
     */
    public function uniteApprentissages(): HasMany
    {
        return $this->hasMany(UniteApprentissage::class, 'micro_competence_id', 'id');
    }



    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->titre ?? "";
    }
}
