<?php


namespace Modules\PkgApprenants\Models;

use Carbon\Carbon;
use Modules\Core\App\Traits\HasDynamicAttributes;
use Modules\PkgApprenants\Models\Base\BaseApprenant;
use Modules\PkgFormation\Models\AnneeFormation;
use Modules\PkgGestionTaches\Models\RealisationTache;
use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Services\ViewStateService;

class Apprenant extends BaseApprenant
{
    protected $appends = ['derniere_tache_terminee_ou_validation'];

    protected $casts = [
        'date_naissance' => 'date', // ou 'date' si vous n’avez pas besoin de l’heure
    ];

    protected $with = [
       'groupes',
       'nationalite',
       'niveauxScolaire',
       'user'
    ];

    protected static function booted()
    {
        // Afficher et utiliser seulement les apprenants active, else dans le cas d'un filtre 
        static::addGlobalScope('inactif', function (Builder $builder) {
            // Accès au ViewStateService
            $viewState = app(ViewStateService::class);
    
            // Nom du modèle utilisé pour les filtres (adapter si besoin)
            $modelName = 'apprenant';
    
            // Récupérer les filtres appliqués via le ViewState
            $filters = $viewState->getFilterVariables($modelName);
    
            // Si le filtre 'actif' est défini, ne pas appliquer le scope
            if (array_key_exists('actif', $filters)) {
                return;
            }
    
            // Sinon, appliquer le scope global actif = true
            $builder->where('actif', true);
        });
    }
    

    public function getFormateurId()
    {
        return optional($this->realisationProjets->first()?->affectationProjet?->projet?->formateur)->id;
    }

    public function __toString()
    {
        return ($this->nom ?? "") . " " . $this->prenom ?? "";
    }

    /**
     * Obtenir le nombre de réalisations de tâches en cours pour cet apprenant.
     *
     * @return int
     */
    // public function getNombreRealisationTachesEnCoursAttribute(): int
    // {
    //     return $this->queryRealisationTachesEnCours()->count();
    // }

    /**
     * Construire la requête pour récupérer les tâches en cours
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    // public function queryRealisationTachesEnCours()
    // {
    //     return RealisationTache::whereHas('realisationProjet', function ($query) {
    //             $query->where('apprenant_id', $this->id);
    //         })
    //         ->whereHas('etatRealisationTache', function ($q) {
    //             $q->where('nom', 'En cours'); // Filtrer uniquement les tâches en cours
    //         });


    //         // $subQuery->whereHas('realisationTaches', function ($q) {
    //         //     $q->whereHas('etatRealisationTache', function ($etat) {
    //         //         $etat->where('nom', 'En cours'); // Filtrer uniquement les tâches en cours
    //         //     });
    //         // });
    // }


       /**
     * Récupère le groupe en cours pour l'année de formation actuelle.
     *
     * @return \Modules\PkgApprenants\Models\Groupe|null
     */
    public function groupeEnCours()
    {
        $anneeActuelle = AnneeFormation::query()
            ->whereYear('date_debut', '<=', Carbon::now()->year)
            ->whereYear('date_fin', '>=', Carbon::now()->year)
            ->first();

        if (!$anneeActuelle) return null;

        return $this->groupes()
            ->where('annee_formation_id', $anneeActuelle->id)
            ->first();
    }
    public function getGroupeAttribute()
    {
        return $this->groupeEnCours();
    }


   

    public function getDerniereTacheTermineeOuValidationAttribute()
    {
        $taches = $this->realisationProjets
            ->flatMap->realisationTaches
            ->filter(function ($tache) {
                $ref = $tache->etatRealisationTache?->workflowTache?->code;
                return in_array($ref, ['TERMINEE', 'EN_VALIDATION']);
            });

        return $taches->sortByDesc('updated_at')->first();
    }

}
