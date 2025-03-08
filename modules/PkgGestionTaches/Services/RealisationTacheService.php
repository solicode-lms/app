<?php

namespace Modules\PkgGestionTaches\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgGestionTaches\Database\Seeders\EtatRealisationTacheSeeder;
use Modules\PkgGestionTaches\Models\EtatRealisationTache;
use Modules\PkgGestionTaches\Models\Tache;
use Modules\PkgGestionTaches\Services\Base\BaseRealisationTacheService;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;

/**
 * Classe RealisationTacheService pour gérer la persistance de l'entité RealisationTache.
 */
class RealisationTacheService extends BaseRealisationTacheService
{
   
    public function dataCalcul($realisationTache)
    {
        // En Cas d'édit
        if(isset($realisationTache->id)){
          
        }
      
        return $realisationTache;
    }

    // TODO : Gapp : ajouter un metaData filterDataSource : indique la méthode à utiliser pour trouver data
    // il faut indiquer aussi, plusieurs méthode si les données sont par rôle : 
    // Exemple : formateur, apprenant, all
    public function initFieldsFilterable()
    {

        $scopeVariables = $this->viewState->getScopeVariables('realisationTache');
        $this->fieldsFilterable = [];
        $sessionState = $this->sessionState;


        // AffectationProjet
        $affectationProjetService = new AffectationProjetService();
        $affectationProjets = match (true) {
            Auth::user()->hasRole(Role::FORMATEUR_ROLE) => $affectationProjetService->getAffectationProjetsByFormateurId($sessionState->get("formateur_id")),
            Auth::user()->hasRole(Role::APPRENANT_ROLE) => $affectationProjetService->getAffectationProjetsByApprenantId($sessionState->get("apprenant_id")),
            default => AffectationProjet::all(),
        };
        $this->fieldsFilterable[] = $this->generateRelationFilter(
            __("PkgRealisationProjets::affectationProjet.plural"), 
            'realisationProjet.affectation_projet_id', 
            AffectationProjet::class, 
            "id",
            $affectationProjets, 
            "[name='tache_id']",
            route('taches.getTacheByAffectationProjetId',  ['affectation_projet_id' => '__ID__']));
       
        // Etat
        $etatRealisationTacheService = new EtatRealisationTacheService();
        $etatRealisationTaches = match (true) {
            Auth::user()->hasRole(Role::FORMATEUR_ROLE) => $etatRealisationTacheService->getEtatRealisationTacheByFormateurId($sessionState->get("formateur_id")),
            Auth::user()->hasRole(Role::APPRENANT_ROLE) => $etatRealisationTacheService->getEtatRealisationTacheByFormateurDApprenantId($sessionState->get("apprenant_id")),
            default => Tache::all(),
        };
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(
            __("PkgGestionTaches::etatRealisationTache.plural"), 
            'etat_realisation_tache_id', 
            \Modules\PkgGestionTaches\Models\EtatRealisationTache::class, 
            'nom',
            $etatRealisationTaches);

        // Apprenant
        // TODO : Gapp add MetaData relationFilter
        $apprenants = match (true) {
            Auth::user()->hasRole(Role::FORMATEUR_ROLE) => (new FormateurService())->getApprenants($this->sessionState->get("formateur_id")),
            Auth::user()->hasRole(Role::APPRENANT_ROLE) => (new ApprenantService())->getApprenantsDeGroupe($this->sessionState->get("apprenant_id")),
            default => Apprenant::all(),
        };
        $this->fieldsFilterable[] = $this->generateRelationFilter(
            __("PkgApprenants::apprenant.plural"), 
            'realisationProjet.apprenant_id', 
            \Modules\PkgApprenants\Models\Apprenant::class,
            "id",
            $apprenants);

        // Tâches
        $tacheService = new TacheService();
        $taches = match (true) {
            Auth::user()->hasRole(Role::FORMATEUR_ROLE) => $tacheService->getTacheByFormateurId($sessionState->get("formateur_id")),
            Auth::user()->hasRole(Role::APPRENANT_ROLE) => $tacheService->getTacheByApprenantId($sessionState->get("apprenant_id")),
            default => Tache::all(),
        };
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(
            __("PkgGestionTaches::tache.plural"),
            'tache_id',
            \Modules\PkgGestionTaches\Models\Tache::class,
            'titre',
            $taches
        );
        

        
    }


 /**
 * Paginer les réalisations de tâches en les triant par la priorité de la tâche associée,
 * tout en incluant celles qui n'ont pas de priorité.
 *
 * @param array $params
 * @param int $perPage
 * @param array $columns
 * @return LengthAwarePaginator
 */
public function paginate(array $params = [], int $perPage = 0, array $columns = ['*']): LengthAwarePaginator
{
    $perPage = $perPage ?: $this->paginationLimit;

    return $this->model::withScope(function () use ($params, $perPage, $columns) {
        $query = $this->allQuery($params);

        // Joindre les tables Tache et PrioriteTache avec LEFT JOIN pour inclure les tâches sans priorité
        $query->leftJoin('taches', 'realisation_taches.tache_id', '=', 'taches.id')
              ->leftJoin('priorite_taches', 'taches.priorite_tache_id', '=', 'priorite_taches.id')
              ->orderByRaw('COALESCE(priorite_taches.ordre, 9999) ASC') // Trier par priorité (les NULL en dernier)
              ->select('realisation_taches.*'); // Sélectionner les colonnes de la table principale

        // Calcul du nombre total des résultats filtrés
        $this->totalFilteredCount = $query->count();

        return $query->paginate($perPage, $columns);
    });
}



public function update($id, array $data)
{
    $record = $this->find($id);

    if (!empty($data["etat_realisation_tache_id"])) {
        $etat_realisation_tache_id = $data["etat_realisation_tache_id"];
        $nouvelEtat = EtatRealisationTache::find($etat_realisation_tache_id);

        // Vérifier si le nouvel état existe
        if ($nouvelEtat) {
            // Empêcher un apprenant d'affecter un état réservé aux formateurs
            if ($nouvelEtat->is_editable_only_by_formateur && !Auth::user()->hasRole(Role::FORMATEUR_ROLE)) {
                throw ValidationException::withMessages([
                    'etat_realisation_tache_id' => "Seul un formateur peut affecter cet état de tâche."
                ]);
            }
        }

        // Vérification si l'état actuel existe et est modifiable uniquement par un formateur
        if ($record->etatRealisationTache) {
            if (
                $record->etatRealisationTache->is_editable_only_by_formateur
                && $record->etatRealisationTache->id != $etat_realisation_tache_id
                && !Auth::user()->hasRole(Role::FORMATEUR_ROLE)
            ) {
                throw ValidationException::withMessages([
                    'etat_realisation_tache_id' => "Cet état de projet doit être modifié par le formateur."
                ]);
            }
        }
    }

    // Mise à jour standard du projet
    return parent::update($id, $data);
}



}
