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
use Modules\PkgGestionTaches\Models\TypeDependanceTache;

/**
 * Classe BaseDependanceTache
 * Cette classe sert de base pour le modèle DependanceTache.
 */
class BaseDependanceTache extends BaseModel
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
        'tache_id', 'tache_cible_id', 'type_dependance_tache_id'
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
     * Relation BelongsTo pour Tache.
     *
     * @return BelongsTo
     */
    public function tacheCible(): BelongsTo
    {
        return $this->belongsTo(Tache::class, 'tache_cible_id', 'id');
    }
    /**
     * Relation BelongsTo pour TypeDependanceTache.
     *
     * @return BelongsTo
     */
    public function typeDependanceTache(): BelongsTo
    {
        return $this->belongsTo(TypeDependanceTache::class, 'type_dependance_tache_id', 'id');
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
