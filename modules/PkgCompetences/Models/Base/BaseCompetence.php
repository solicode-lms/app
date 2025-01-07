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
use Modules\PkgCompetences\Models\Module;
use Modules\PkgCompetences\Models\NiveauCompetence;
use Modules\PkgCompetences\Models\Technology;
use Modules\PkgCreationProjet\Models\TransfertCompetence;

/**
 * Classe BaseCompetence
 * Cette classe sert de base pour le modèle Competence.
 */
class BaseCompetence extends BaseModel
{
    use HasFactory, HasDynamicContext;

    public function __construct() {
        parent::__construct(); 
        $this->isOwnedByUser =  false;
    }

    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'nom', 'description', 'module_id'
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
     * Relation HasMany pour NiveauCompetences.
     *
     * @return HasMany
     */
    public function niveauCompetences(): HasMany
    {
        return $this->hasMany(NiveauCompetence::class, 'competence_id', 'id');
    }
    /**
     * Relation HasMany pour TransfertCompetences.
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
        return $this->code;
    }
}
