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
use Modules\PkgCompetences\Models\Technology;
use Modules\PkgCompetences\Models\NiveauCompetence;
use Modules\PkgAutoformation\Models\Formation;
use Modules\PkgCreationProjet\Models\TransfertCompetence;

/**
 * Classe BaseCompetence
 * Cette classe sert de base pour le modèle Competence.
 */
class BaseCompetence extends BaseModel
{
    use HasFactory, HasDynamicContext;

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
        $this->isOwnedByUser =  false;
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'mini_code', 'nom', 'module_id', 'description'
    ];
    public $manyToMany = [
        'Technology' => ['relation' => 'technologies' , "foreign_key" => "technology_id" ]
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
     * Relation ManyToMany pour Technologies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'competence_technology');
    }

    /**
     * Relation HasMany pour Competences.
     *
     * @return HasMany
     */
    public function niveauCompetences(): HasMany
    {
        return $this->hasMany(NiveauCompetence::class, 'competence_id', 'id');
    }
    /**
     * Relation HasMany pour Competences.
     *
     * @return HasMany
     */
    public function formations(): HasMany
    {
        return $this->hasMany(Formation::class, 'competence_id', 'id');
    }
    /**
     * Relation HasMany pour Competences.
     *
     * @return HasMany
     */
    public function transfertCompetences(): HasMany
    {
        return $this->hasMany(TransfertCompetence::class, 'competence_id', 'id');
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
