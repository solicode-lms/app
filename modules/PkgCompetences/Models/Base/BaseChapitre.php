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
use Modules\PkgCompetences\Models\UniteApprentissage;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgApprentissage\Models\RealisationChapitre;

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
      //  'uniteApprentissage',
      //  'formateur'
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
        'ordre', 'code', 'nom', 'lien', 'description', 'duree_en_heure', 'isOfficiel', 'unite_apprentissage_id', 'formateur_id'
    ];
    public $manyToOne = [
        'UniteApprentissage' => [
            'model' => "Modules\\PkgCompetences\\Models\\UniteApprentissage",
            'relation' => 'uniteApprentissages' , 
            "foreign_key" => "unite_apprentissage_id", 
            ],
        'Formateur' => [
            'model' => "Modules\\PkgFormation\\Models\\Formateur",
            'relation' => 'formateurs' , 
            "foreign_key" => "formateur_id", 
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
     * Relation BelongsTo pour Formateur.
     *
     * @return BelongsTo
     */
    public function formateur(): BelongsTo
    {
        return $this->belongsTo(Formateur::class, 'formateur_id', 'id');
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
        return $this->code ?? "";
    }
}
