<?php


namespace Modules\PkgCreationProjet\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\PkgCreationProjet\Models\Base\BaseProjet;
use Modules\PkgGestionTaches\Models\Tache;

class Projet extends BaseProjet
{

    protected $with = [
       'filiere',
       'formateur',
    //    'transfertCompetences',
    //    'affectationProjets',
    //    'taches',
       'livrables',
       'resources',
    ];
        /**
     * Relation HasMany pour Projets.
     *
     * @return HasMany
     */
    public function taches(): HasMany
    {
        return $this->hasMany(Tache::class, 'projet_id', 'id')
        ->orderBy('ordre','asc');
    }

 

    public static $user_column_name = "formateur_id";

    /**
     * Attribut dynamique total_notes
     * 
     * @return float
     */
    public function getTotalNotesAttribute(): float
    {
        return (float) $this->taches()->sum('note');
    }
}
