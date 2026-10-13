<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgTableauBord\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgFormation\Models\Module;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgFormation\Models\AnneeFormation;
use Modules\PkgAutorisation\Models\User;

/**
 * Classe BaseBordereauxOfficiel
 * Cette classe sert de base pour le modèle BordereauxOfficiel.
 */
class BaseBordereauxOfficiel extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'module',
      //  'groupe',
      //  'anneeFormation',
      //  'genereParUser'
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
        'reference', 'module_id', 'groupe_id', 'annee_formation_id', 'nb_apprenants', 'taux_conformite_calcul', 'statut', 'date_scellement', 'hash_signature', 'genere_par_user_id'
    ];
    public $manyToOne = [
        'module' => [
            'model' => "Modules\\PkgFormation\\Models\\Module",
            'relation' => 'module' , 
            "foreign_key" => "module_id", 
            ],
        'groupe' => [
            'model' => "Modules\\PkgApprenants\\Models\\Groupe",
            'relation' => 'groupe' , 
            "foreign_key" => "groupe_id", 
            ],
        'anneeFormation' => [
            'model' => "Modules\\PkgFormation\\Models\\AnneeFormation",
            'relation' => 'anneeFormation' , 
            "foreign_key" => "annee_formation_id", 
            ],
        'genereParUser' => [
            'model' => "Modules\\PkgAutorisation\\Models\\User",
            'relation' => 'genereParUser' , 
            "foreign_key" => "genere_par_user_id", 
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
     * Relation BelongsTo pour Groupe.
     *
     * @return BelongsTo
     */
    public function groupe(): BelongsTo
    {
        return $this->belongsTo(Groupe::class, 'groupe_id', 'id');
    }
    /**
     * Relation BelongsTo pour AnneeFormation.
     *
     * @return BelongsTo
     */
    public function anneeFormation(): BelongsTo
    {
        return $this->belongsTo(AnneeFormation::class, 'annee_formation_id', 'id');
    }
    /**
     * Relation BelongsTo pour User.
     *
     * @return BelongsTo
     */
    public function genereParUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'genere_par_user_id', 'id');
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
