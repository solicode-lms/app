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
use Modules\PkgUtilisateurs\Models\Formateur;
use Modules\PkgCreationProjet\Models\Livrable;
use Modules\PkgCreationProjet\Models\Resource;
use Modules\PkgCreationProjet\Models\TransfertCompetence;

/**
 * Classe BaseProjet
 * Cette classe sert de base pour le modèle Projet.
 */
class BaseProjet extends BaseModel
{
    use HasFactory, HasDynamicContext, OwnedByUser;

    public function __construct() {
        parent::__construct(); 
        $this->isOwnedByUser =  true;
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'titre', 'travail_a_faire', 'critere_de_travail', 'description', 'date_debut', 'date_fin', 'formateur_id'
    ];

    /**
     * Relation BelongsTo pour Formateur.
     *
     * @return BelongsTo
     */
    public function formateur(): BelongsTo
    {
        return $this->belongsTo(Formateur::class, 'formateur_id', 'id');
    }


    /**
     * Relation HasMany pour Projets.
     *
     * @return HasMany
     */
    public function livrables(): HasMany
    {
        return $this->hasMany(Livrable::class, 'projet_id', 'id');
    }
    /**
     * Relation HasMany pour Projets.
     *
     * @return HasMany
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class, 'projet_id', 'id');
    }
    /**
     * Relation HasMany pour Projets.
     *
     * @return HasMany
     */
    public function transfertCompetences(): HasMany
    {
        return $this->hasMany(TransfertCompetence::class, 'projet_id', 'id');
    }



    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->titre;
    }
}
