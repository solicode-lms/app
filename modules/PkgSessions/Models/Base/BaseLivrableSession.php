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
use Modules\PkgSessions\Models\SessionFormation;
use Modules\PkgCreationProjet\Models\NatureLivrable;

/**
 * Classe BaseLivrableSession
 * Cette classe sert de base pour le modèle LivrableSession.
 */
class BaseLivrableSession extends BaseModel
{
    use HasFactory, HasDynamicContext;

    /**
     * Eager-load par défaut les relations belongsTo listées dans manyToOne
     *
     * @var array
     */
    protected $with = [
      //  'sessionFormation',
      //  'natureLivrable'
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
        'ordre', 'titre', 'description', 'session_formation_id', 'nature_livrable_id'
    ];
    public $manyToOne = [
        'SessionFormation' => [
            'model' => "Modules\\PkgSessions\\Models\\SessionFormation",
            'relation' => 'sessionFormations' , 
            "foreign_key" => "session_formation_id", 
            ],
        'NatureLivrable' => [
            'model' => "Modules\\PkgCreationProjet\\Models\\NatureLivrable",
            'relation' => 'natureLivrables' , 
            "foreign_key" => "nature_livrable_id", 
            ]
    ];


    /**
     * Relation BelongsTo pour SessionFormation.
     *
     * @return BelongsTo
     */
    public function sessionFormation(): BelongsTo
    {
        return $this->belongsTo(SessionFormation::class, 'session_formation_id', 'id');
    }
    /**
     * Relation BelongsTo pour NatureLivrable.
     *
     * @return BelongsTo
     */
    public function natureLivrable(): BelongsTo
    {
        return $this->belongsTo(NatureLivrable::class, 'nature_livrable_id', 'id');
    }





    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->titre ?? "";
    }
}
