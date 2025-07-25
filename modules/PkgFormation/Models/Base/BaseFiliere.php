<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgFormation\Models\Module;
use Modules\PkgCreationProjet\Models\Projet;
use Modules\PkgSessions\Models\SessionFormation;

/**
 * Classe BaseFiliere
 * Cette classe sert de base pour le modèle Filiere.
 */
class BaseFiliere extends BaseModel
{
    use HasFactory, HasDynamicContext;


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
        'code', 'nom', 'description'
    ];




    /**
     * Relation HasMany pour Filieres.
     *
     * @return HasMany
     */
    public function groupes(): HasMany
    {
        return $this->hasMany(Groupe::class, 'filiere_id', 'id');
    }
    /**
     * Relation HasMany pour Filieres.
     *
     * @return HasMany
     */
    public function modules(): HasMany
    {
        return $this->hasMany(Module::class, 'filiere_id', 'id');
    }
    /**
     * Relation HasMany pour Filieres.
     *
     * @return HasMany
     */
    public function projets(): HasMany
    {
        return $this->hasMany(Projet::class, 'filiere_id', 'id');
    }
    /**
     * Relation HasMany pour Filieres.
     *
     * @return HasMany
     */
    public function sessionFormations(): HasMany
    {
        return $this->hasMany(SessionFormation::class, 'filiere_id', 'id');
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
