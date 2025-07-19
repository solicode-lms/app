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
use Modules\PkgCompetences\Models\Chapitre;

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
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'ordre', 'code', 'nom', 'lien', 'description', 'micro_competence_id'
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
    public function chapitres(): HasMany
    {
        return $this->hasMany(Chapitre::class, 'unite_apprentissage_id', 'id');
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
