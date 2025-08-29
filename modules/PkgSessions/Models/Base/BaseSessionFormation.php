<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgSessions\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgFormation\Models\Filiere;
use Modules\PkgFormation\Models\AnneeFormation;
use Modules\PkgSessions\Models\AlignementUa;
use Modules\PkgSessions\Models\LivrableSession;
use Modules\PkgCreationProjet\Models\Projet;

/**
 * Classe BaseSessionFormation
 * Cette classe sert de base pour le modèle SessionFormation.
 */
class BaseSessionFormation extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'filiere',
      //  'anneeFormation'
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
        'ordre', 'titre', 'code', 'thematique', 'filiere_id', 'objectifs_pedagogique', 'titre_prototype', 'description_prototype', 'contraintes_prototype', 'titre_projet', 'description_projet', 'contraintes_projet', 'remarques', 'date_debut', 'date_fin', 'jour_feries_vacances', 'annee_formation_id'
    ];
    public $manyToOne = [
        'Filiere' => [
            'model' => "Modules\\PkgFormation\\Models\\Filiere",
            'relation' => 'filieres' , 
            "foreign_key" => "filiere_id", 
            ],
        'AnneeFormation' => [
            'model' => "Modules\\PkgFormation\\Models\\AnneeFormation",
            'relation' => 'anneeFormations' , 
            "foreign_key" => "annee_formation_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour Filiere.
     *
     * @return BelongsTo
     */
    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class, 'filiere_id', 'id');
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
     * Relation HasMany pour SessionFormations.
     *
     * @return HasMany
     */
    public function alignementUas(): HasMany
    {
        return $this->hasMany(AlignementUa::class, 'session_formation_id', 'id');
    }
    /**
     * Relation HasMany pour SessionFormations.
     *
     * @return HasMany
     */
    public function livrableSessions(): HasMany
    {
        return $this->hasMany(LivrableSession::class, 'session_formation_id', 'id');
    }
    /**
     * Relation HasMany pour SessionFormations.
     *
     * @return HasMany
     */
    public function projets(): HasMany
    {
        return $this->hasMany(Projet::class, 'session_formation_id', 'id');
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
