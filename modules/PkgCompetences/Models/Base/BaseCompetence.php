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
use Modules\PkgFormation\Models\Module;
use Modules\PkgCompetences\Models\MicroCompetence;

/**
 * Classe BaseCompetence
 * Cette classe sert de base pour le modèle Competence.
 */
class BaseCompetence extends BaseModel
{
    use HasFactory, HasDynamicContext, OwnedByUser;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'module'
    ];


    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  true;
        $this->ownerRelationPath = "module.filiere.groupes.formateurs.user,module.filiere.groupes.apprenants.user";
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'mini_code', 'nom', 'module_id', 'description'
    ];
    public $manyToOne = [
        'Module' => [
            'model' => "Modules\\PkgFormation\\Models\\Module",
            'relation' => 'modules' , 
            "foreign_key" => "module_id", 
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
     * Relation HasMany pour Competences.
     *
     * @return HasMany
     */
    public function microCompetences(): HasMany
    {
        return $this->hasMany(MicroCompetence::class, 'competence_id', 'id');
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
