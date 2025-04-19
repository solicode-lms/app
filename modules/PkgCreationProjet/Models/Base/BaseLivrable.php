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
use Modules\PkgCreationProjet\Models\NatureLivrable;
use Modules\PkgCreationProjet\Models\Projet;
use Modules\PkgGestionTaches\Models\Tache;
use Modules\PkgRealisationProjets\Models\LivrablesRealisation;

/**
 * Classe BaseLivrable
 * Cette classe sert de base pour le modèle Livrable.
 */
class BaseLivrable extends BaseModel
{
    use HasFactory, HasDynamicContext, OwnedByUser;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  true;
        $this->ownerRelationPath = "projet.formateur.user";
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'nature_livrable_id', 'titre', 'projet_id', 'description', 'is_affichable_seulement_par_formateur'
    ];
    public $manyToMany = [
        'Tache' => ['relation' => 'taches' , "foreign_key" => "tache_id" ]
    ];
    public $manyToOne = [
        'NatureLivrable' => [
            'model' => "Modules\\PkgCreationProjet\\Models\\NatureLivrable",
            'relation' => 'natureLivrables' , 
            "foreign_key" => "nature_livrable_id", 
            ],
        'Projet' => [
            'model' => "Modules\\PkgCreationProjet\\Models\\Projet",
            'relation' => 'projets' , 
            "foreign_key" => "projet_id", 
            ]
    ];


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
     * Relation BelongsTo pour Projet.
     *
     * @return BelongsTo
     */
    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class, 'projet_id', 'id');
    }

    /**
     * Relation ManyToMany pour Taches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function taches()
    {
        return $this->belongsToMany(Tache::class, 'livrable_tache');
    }

    /**
     * Relation HasMany pour Livrables.
     *
     * @return HasMany
     */
    public function livrablesRealisations(): HasMany
    {
        return $this->hasMany(LivrablesRealisation::class, 'livrable_id', 'id');
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
