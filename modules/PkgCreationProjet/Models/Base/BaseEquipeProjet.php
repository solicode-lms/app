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
use Modules\Core\Models\SysColor;
use Modules\PkgCreationProjet\Models\Projet;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgCreationTache\Models\Tache;

/**
 * Classe BaseEquipeProjet
 * Cette classe sert de base pour le modèle EquipeProjet.
 */
class BaseEquipeProjet extends BaseModel
{
    use HasFactory, HasDynamicContext, OwnedByUser;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'sysColor',
      //  'projet'
    ];


    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  true;
        $this->ownerRelationPath = "Projet.formateur.user";
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'nom', 'description', 'sys_color_id', 'reference', 'projet_id'
    ];
    public $manyToMany = [
        'Apprenant' => ['relation' => 'apprenants' , "foreign_key" => "apprenant_id" ]
    ];
    public $manyToOne = [
        'sysColor' => [
            'model' => "Modules\\Core\\Models\\SysColor",
            'relation' => 'sysColor' , 
            "foreign_key" => "sys_color_id", 
            ],
        'projet' => [
            'model' => "Modules\\PkgCreationProjet\\Models\\Projet",
            'relation' => 'projet' , 
            "foreign_key" => "projet_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour SysColor.
     *
     * @return BelongsTo
     */
    public function sysColor(): BelongsTo
    {
        return $this->belongsTo(SysColor::class, 'sys_color_id', 'id');
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
     * Relation ManyToMany pour Apprenants.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function apprenants()
    {
        return $this->belongsToMany(Apprenant::class, 'apprenant_equipe_projet');
    }

    /**
     * Relation HasMany pour EquipeProjets.
     *
     * @return HasMany
     */
    public function taches(): HasMany
    {
        return $this->hasMany(Tache::class, 'equipe_projet_id', 'id');
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
