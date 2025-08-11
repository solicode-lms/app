<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgFormation\Models\Module;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprentissage\Models\EtatRealisationModule;
use Modules\PkgApprentissage\Models\RealisationCompetence;

/**
 * Classe BaseRealisationModule
 * Cette classe sert de base pour le modèle RealisationModule.
 */
class BaseRealisationModule extends BaseModel
{
    use HasFactory, HasDynamicContext, OwnedByUser;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'module',
      //  'apprenant',
      //  'etatRealisationModule'
    ];


    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  true;
        $this->ownerRelationPath = "apprenant.groupes.formateurs.user,apprenant.user";
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'module_id', 'apprenant_id', 'progression_cache', 'etat_realisation_module_id', 'note_cache', 'bareme_cache', 'dernier_update', 'commentaire_formateur', 'date_debut', 'date_fin'
    ];
    public $manyToOne = [
        'Module' => [
            'model' => "Modules\\PkgFormation\\Models\\Module",
            'relation' => 'modules' , 
            "foreign_key" => "module_id", 
            ],
        'Apprenant' => [
            'model' => "Modules\\PkgApprenants\\Models\\Apprenant",
            'relation' => 'apprenants' , 
            "foreign_key" => "apprenant_id", 
            ],
        'EtatRealisationModule' => [
            'model' => "Modules\\PkgApprentissage\\Models\\EtatRealisationModule",
            'relation' => 'etatRealisationModules' , 
            "foreign_key" => "etat_realisation_module_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour Module.
     *
     * @return BelongsTo
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }
    /**
     * Relation BelongsTo pour Apprenant.
     *
     * @return BelongsTo
     */
    public function apprenant(): BelongsTo
    {
        return $this->belongsTo(Apprenant::class, 'apprenant_id', 'id');
    }
    /**
     * Relation BelongsTo pour EtatRealisationModule.
     *
     * @return BelongsTo
     */
    public function etatRealisationModule(): BelongsTo
    {
        return $this->belongsTo(EtatRealisationModule::class, 'etat_realisation_module_id', 'id');
    }


    /**
     * Relation HasMany pour RealisationModules.
     *
     * @return HasMany
     */
    public function realisationCompetences(): HasMany
    {
        return $this->hasMany(RealisationCompetence::class, 'realisation_module_id', 'id');
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
