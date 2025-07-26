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
use Modules\PkgCompetences\Models\UniteApprentissage;
use Modules\PkgCreationProjet\Models\Projet;

/**
 * Classe BaseMobilisationUa
 * Cette classe sert de base pour le modèle MobilisationUa.
 */
class BaseMobilisationUa extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'uniteApprentissage',
      //  'projet'
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
        'unite_apprentissage_id', 'bareme_evaluation_prototype', 'criteres_evaluation_prototype', 'bareme_evaluation_projet', 'criteres_evaluation_projet', 'description', 'projet_id'
    ];
    public $manyToOne = [
        'UniteApprentissage' => [
            'model' => "Modules\\PkgCompetences\\Models\\UniteApprentissage",
            'relation' => 'uniteApprentissages' , 
            "foreign_key" => "unite_apprentissage_id", 
            ],
        'Projet' => [
            'model' => "Modules\\PkgCreationProjet\\Models\\Projet",
            'relation' => 'projets' , 
            "foreign_key" => "projet_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour UniteApprentissage.
     *
     * @return BelongsTo
     */
    public function uniteApprentissage(): BelongsTo
    {
        return $this->belongsTo(UniteApprentissage::class, 'unite_apprentissage_id', 'id');
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
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->id ?? "";
    }
}
