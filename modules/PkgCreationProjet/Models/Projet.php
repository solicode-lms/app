<?php


namespace Modules\PkgCreationProjet\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\PkgCreationProjet\Models\Base\BaseProjet;
use Modules\PkgCreationTache\Models\Tache;

class Projet extends BaseProjet
{

     protected $with = [
       'filiere',
       'formateur'
    ];


    // protected $with = [
    //    'filiere',
    //    'formateur',
    // //    'transfertCompetences',
    // //    'affectationProjets',
    // //    'taches',
    //    'livrables',
    //    'resources',
    // ];
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

    public function generateReference(): string
    {
       return $this->titre . (!empty($this->formateur) ? '-' . $this->formateur->reference : '');
    }

    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        $titre = $this->titre ?? "";
        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->hasAnyRole(['admin-formateur', 'admin'])) {
            if ($this->formateur) {
                return $titre . " [ " . $this->formateur . "]";
            }
        }
        return $titre;
    }
}
