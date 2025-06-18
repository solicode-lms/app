<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgRealisationProjets\Models\AffectationProjet;

/**
 * Classe BaseSousGroupe
 * Cette classe sert de base pour le modèle SousGroupe.
 */
class BaseSousGroupe extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'groupe'
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
        'nom', 'description', 'groupe_id'
    ];
    public $manyToMany = [
        'Apprenant' => ['relation' => 'apprenants' , "foreign_key" => "apprenant_id" ]
    ];
    public $manyToOne = [
        'Groupe' => [
            'model' => "Modules\\PkgApprenants\\Models\\Groupe",
            'relation' => 'groupes' , 
            "foreign_key" => "groupe_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour Groupe.
     *
     * @return BelongsTo
     */
    public function groupe(): BelongsTo
    {
        return $this->belongsTo(Groupe::class, 'groupe_id', 'id');
    }

    /**
     * Relation ManyToMany pour Apprenants.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function apprenants()
    {
        return $this->belongsToMany(Apprenant::class, 'apprenant_sous_groupe');
    }

    /**
     * Relation HasMany pour SousGroupes.
     *
     * @return HasMany
     */
    public function affectationProjets(): HasMany
    {
        return $this->hasMany(AffectationProjet::class, 'sous_groupe_id', 'id');
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
