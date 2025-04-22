<?php


namespace Modules\PkgRealisationProjets\Services;

use Illuminate\Support\Facades\Auth;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgGestionTaches\Services\EtatRealisationTacheService;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationProjets\Services\Base\BaseRealisationProjetService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\PkgRealisationProjets\Models\EtatsRealisationProjet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgGestionTaches\Models\EtatRealisationTache;
use Modules\PkgGestionTaches\Models\Tache;
use Modules\PkgGestionTaches\Services\RealisationTacheService;
use Modules\PkgRealisationProjets\Models\WorkflowProjet;

/**
 * 
 * Classe RealisationProjetService pour gérer la persistance de l'entité RealisationProjet.
 */
class RealisationProjetService extends BaseRealisationProjetService
{

    public function initFieldsFilterable(){

        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationProjet');
        $this->fieldsFilterable = [];

        // Groupe 
        if(!Auth::user()->hasAnyRole(Role::FORMATEUR_ROLE,Role::APPRENANT_ROLE) || !empty($this->viewState->get("filter.realisationProjet.AffectationProjet.Groupe_id") ) ) {
            // Affichage de l'état de solicode
            $groupeService = new GroupeService();
            $groupes = $groupeService->all();
            $this->fieldsFilterable[] = $this->generateRelationFilter(
                __("PkgApprenants::Groupe.plural"), 
                'AffectationProjet.Groupe_id', 
                Groupe::class, 
                "code",
                "id",
                $groupes,
                "[name='affectation_projet_id']",
                route('affectationProjets.getData'),
                "groupe_id"
            );
        }

        // AffectationProjet
        if(Auth::user()->hasRole(Role::FORMATEUR_ROLE)){
            $affectationProjets = (new AffectationProjetService())->getAffectationProjetsByFormateurId($this->sessionState->get("formateur_id"));
        } elseif (Auth::user()->hasRole(Role::APPRENANT_ROLE)){
            $affectationProjets = (new AffectationProjetService())->getAffectationProjetsByApprenantId($this->sessionState->get("apprenant_id"));
        } else{
            $affectationProjets = AffectationProjet::all();
        }
        $this->fieldsFilterable[] =  $this->generateManyToOneFilter(
            __("PkgRealisationProjets::affectationProjet.plural"), 
            'affectation_projet_id', 
            \Modules\PkgRealisationProjets\Models\AffectationProjet::class, 
            'id',
            $affectationProjets);

        // Apprenant
        if(Auth::user()->hasRole(Role::FORMATEUR_ROLE)){
            $apprenants = (new FormateurService())->getApprenants($this->sessionState->get("formateur_id"));
        } elseif (Auth::user()->hasRole(Role::APPRENANT_ROLE)){
            $apprenants = (new ApprenantService())->getApprenantsDeGroupe($this->sessionState->get("apprenant_id"));
        } else{
            $apprenants = Apprenant::all();
        }
        $this->fieldsFilterable[] =  $this->generateManyToOneFilter(
            __("PkgApprenants::apprenant.plural"), 
            'apprenant_id', 
            \Modules\PkgApprenants\Models\Apprenant::class, 
            'nom',
            $apprenants);


        // If formateur ou apprenant
        if(Auth::user()->hasAnyRole(Role::FORMATEUR_ROLE,Role::APPRENANT_ROLE)){
            // Affichage des état de formateur
            $etatsRealisationProjetService = new EtatsRealisationProjetService();
            $etatsRealisationProjets = match (true) {
                Auth::user()->hasRole(Role::FORMATEUR_ROLE) => $etatsRealisationProjetService->getByFormateur($this->sessionState->get("formateur_id")),
                Auth::user()->hasRole(Role::APPRENANT_ROLE) => $etatsRealisationProjetService->getEtatsByFormateurPrincipalForApprenant($this->sessionState->get("apprenant_id")),
                default => $etatsRealisationProjetService->all(),
            };
            $this->fieldsFilterable[] =   $this->generateManyToOneFilter(
                __("PkgRealisationProjets::etatsRealisationProjet.plural"), 
                'etats_realisation_projet_id', 
                \Modules\PkgRealisationProjets\Models\EtatsRealisationProjet::class,
                'titre',$etatsRealisationProjets);
        }
        // Etat - Solicode
        if(!Auth::user()->hasAnyRole(Role::FORMATEUR_ROLE,Role::APPRENANT_ROLE) || !empty($this->viewState->get("filter.realisationTache.EtatRealisationTache.WorkflowTache.Code") ) ) {
            // Affichage de l'état de solicode
            $workflowProjetService = new WorkflowProjetService();
            $workflowProjets = $workflowProjetService->all();
            $this->fieldsFilterable[] = $this->generateRelationFilter(
                __("PkgRealisationProjets::workflowProjet.plural"), 
                'EtatsRealisationProjet.WorkflowProjet.Code', 
                WorkflowProjet::class, 
                "code",
                "code",
                $workflowProjets
            );
        }


     }

    public function dataCalcul($realisationProjet)
    {
        // En Cas d'édit
        if(isset($realisationProjet->id)){
          
        }
      
        return $realisationProjet;
    }



    public function paginate(array $params = [], int $perPage = 0, array $columns = ['*']): LengthAwarePaginator
    {
        $perPage = $perPage ?: $this->paginationLimit;

        return $this->model::withScope(function () use ($params, $perPage, $columns) {
            $query = $this->allQuery($params);

            // Vérification et application du filtre par formateur si disponible
            if (isset($params['formateur_id']) && !empty($params['formateur_id'])) {
                $formateur_id = $params['formateur_id'];

                $query->whereHas('affectationProjet', function ($query) use ($formateur_id) {
                    $query->whereHas('projet', function ($q) use ($formateur_id) {
                        $q->where('formateur_id', $formateur_id);
                    });
                });
            }

            // Filtrer par groupe des apprenants du même groupe
            if (!empty($params['scope_groupe_apprenant_id'])) {
                $apprenant_id = $params['scope_groupe_apprenant_id'];

                $query->whereHas('apprenant', function ($q) use ($apprenant_id) {
                    $q->whereHas('groupes', function ($g) use ($apprenant_id) {
                        $g->whereHas('apprenants', function ($a) use ($apprenant_id) {
                            $a->where('apprenants.id', $apprenant_id);
                        });
                    });
                });
            }

          
            $relationsToLoad = ["affectationProjet","apprenant","etatsRealisationProjet","livrablesRealisations","validations"];
            $query->with(array_unique($relationsToLoad));

            // Calcul du nombre total des résultats filtrés
            $this->totalFilteredCount = $query->count();

            return $query->paginate($perPage, $columns);
        });


       
    }
    
 

    /**
     * Summary of update
     * 
     */
    public function update($id, array $data)
    {
        $record =  $this->find($id);


        if (!empty($data["etats_realisation_projet_id"])) {
            
            $etats_realisation_projet_id = $data["etats_realisation_projet_id"];
    
            // Vérifier si l'état est éditable uniquement par le formateur
            if ($record->etatsRealisationProjet 
                && $record->etatsRealisationProjet->is_editable_by_formateur 
                && $record->etatsRealisationProjet->id  != $etats_realisation_projet_id
                && !Auth::user()->hasRole(Role::FORMATEUR_ROLE)) {
              
                throw ValidationException::withMessages([
                    'etats_realisation_projet_id' => "Cet état de projet doit être modifié par le formateur."
                ]);


                return $record;
            }
        }
    
        // Mise à jour standard du projet
        return parent::update($id, $data);
    }

    

    public function create($data)
    {
        return DB::transaction(function () use ($data) {
            // 🔹 Étape 1 : Création de l'entité RealisationProjet via CrudTrait
            $realisationProjet = parent::create($data);
    
            // 🔹 Étape 2 : Identifier le formateur connecté (si applicable)
            $formateur_id = Auth::user()->hasRole(Role::FORMATEUR_ROLE)
                ? Auth::user()->formateur?->id
                : null;
    
            // 🔹 Étape 3 : Vérifier l'existence du projet lié à l'affectation
            $affectationProjet = $realisationProjet->affectationProjet()->with('projet')->first();
            if (!$affectationProjet || !$affectationProjet->projet) {
                throw new \Exception("Aucun projet associé à cette affectation.");
            }
    
            // 🔹 Étape 4 : Récupérer les tâches du projet
            $taches = Tache::where('projet_id', $affectationProjet->projet_id)->get();
    
            // 🔹 Étape 5 : Récupérer l’état initial défini par le formateur (le cas échéant)
            $etatInitial = $formateur_id
                ? (new EtatRealisationTacheService())->getDefaultEtatByFormateurId($formateur_id)
                : null;
    
            // 🔹 Étape 6 : Création des RealisationTache via le Service dédié
            $realisationTacheService = new RealisationTacheService();
            foreach ($taches as $tache) {
                $realisationTacheService->create([
                    'realisation_projet_id'      => $realisationProjet->id,
                    'tache_id'                   => $tache->id,
                    'etat_realisation_tache_id' => $etatInitial?->id,
                ]);
            }
    
            return $realisationProjet;
        });
    }
    
    
}
