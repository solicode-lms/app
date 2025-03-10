<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgGestionTaches\Models\Tache;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgGestionTaches\Models\EtatRealisationTache;
use Modules\PkgGestionTaches\Models\CommentaireRealisationTache;
use Modules\PkgGestionTaches\Models\HistoriqueRealisationTache;

/**
 * Classe BaseRealisationTache
 * Cette classe sert de base pour le modèle RealisationTache.
 */
class BaseRealisationTache extends BaseModel
{
    use HasFactory, HasDynamicContext, OwnedByUser;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  true;
        $this->ownerRelationPath = "realisationProjet.affectationProjet.projet.formateur.user,realisationProjet.apprenant.user";
        
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'tache_id', 'realisation_projet_id', 'dateDebut', 'dateFin', 'etat_realisation_tache_id', 'remarques_formateur', 'remarques_apprenant'
    ];

    /**
     * Relation BelongsTo pour Tache.
     *
     * @return BelongsTo
     */
    public function tache(): BelongsTo
    {
        return $this->belongsTo(Tache::class, 'tache_id', 'id');
    }
    /**
     * Relation BelongsTo pour RealisationProjet.
     *
     * @return BelongsTo
     */
    public function realisationProjet(): BelongsTo
    {
        return $this->belongsTo(RealisationProjet::class, 'realisation_projet_id', 'id');
    }
    /**
     * Relation BelongsTo pour EtatRealisationTache.
     *
     * @return BelongsTo
     */
    public function etatRealisationTache(): BelongsTo
    {
        return $this->belongsTo(EtatRealisationTache::class, 'etat_realisation_tache_id', 'id');
    }


    /**
     * Relation HasMany pour RealisationTaches.
     *
     * @return HasMany
     */
    public function commentaireRealisationTaches(): HasMany
    {
        return $this->hasMany(CommentaireRealisationTache::class, 'realisation_tache_id', 'id');
    }
    /**
     * Relation HasMany pour RealisationTaches.
     *
     * @return HasMany
     */
    public function historiqueRealisationTaches(): HasMany
    {
        return $this->hasMany(HistoriqueRealisationTache::class, 'realisation_tache_id', 'id');
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
