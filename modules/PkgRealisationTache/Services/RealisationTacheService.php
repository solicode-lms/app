<?php

namespace Modules\PkgRealisationTache\Services;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgCreationTache\Services\TacheService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgCreationTache\Models\Tache;
use Modules\PkgRealisationTache\Models\WorkflowTache;
use Modules\PkgRealisationTache\Services\Base\BaseRealisationTacheService;
use Modules\PkgRealisationTache\Services\RealisationTacheService\RealisationTacheServiceCrud;
use Modules\PkgRealisationTache\Services\RealisationTacheService\RealisationTacheCalculeProgression;

use Modules\PkgRealisationTache\Services\RealisationTacheService\RealisationTacheServiceWidgets;
use Modules\PkgRealisationTache\Services\RealisationTacheService\RealisationTacheWorkflow;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationTacheService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

/**
 * Classe RealisationTacheService pour gérer la persistance de l'entité RealisationTache.
 */
class RealisationTacheService extends BaseRealisationTacheService
{
    use 
        RealisationTacheServiceCrud,
        RealisationTacheServiceWidgets,  
        RealisationTacheWorkflow,
        RealisationTacheCalculeProgression;


      

        protected array $index_with_relations = [
            'tache',
            'realisationChapitres',
            'tacheAffectation',
            'tache.livrables',
            'etatRealisationTache',
            'historiqueRealisationTaches',
            'realisationProjet.apprenant',
            'realisationProjet.affectationProjet',
            'tache.livrables.natureLivrable',
            'livrablesRealisations.livrable.taches',
            'realisationProjet.realisationTaches.tache',
        ];










    /**
     * Liste des champs autorisés à l’édition inline
     */
    public function getFieldsEditable(): array
    {
        return [
            'etat_realisation_tache_id',
            'dateDebut',
            'dateFin',
            'note',
            'is_live_coding',
            'remarques_formateur',
            'remarques_apprenant',
            'remarque_evaluateur',
        ];
    }

    /**
     * Génère un ETag basé sur updated_at
     */
    public function etag(RealisationTache $e): string
    {
        $ver = optional($e->updated_at)->timestamp ?? 0;
        return 'W/"rt-' . $e->id . '-' . $ver . '"';
    }

    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(RealisationTache $e, string $field): array
    {
        $meta = [
            'entity' => 'realisation_tache',
            'id'     => $e->id,
            'field'  => $field,
            'writable' => in_array($field, $this->getFieldsEditable()),
            'etag'   => $this->etag($e),
            'schema_version' => 'v1',
        ];

        switch ($field) {
            case 'etat_realisation_tache_id':
                // $options est une Collection [id => libelle]
                $options = app(EtatRealisationTacheService::class)
                    ->getAllForSelect($e->etatRealisationTache); 

                // Pour le front → transformer en [{value, label}, ...]
                $values = $options->map(fn($entity, $id) => [
                    'value' => (int) $entity->id,
                    'label' => (string) $entity,
                ])->values();

                $meta += [
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        'source' => 'static',
                        'values' => $values,
                    ],
                    // Validation Laravel → accepte uniquement les IDs présents en clé
                    'validation' => [
                        'integer',
                        'in:' . $options->keys()->implode(','),
                    ],
                    'value' => $e->etat_realisation_tache_id,
                ];
                break;

            case 'dateDebut':
            case 'dateFin':
                $meta += [
                    'type' => 'date',
                    'validation' => ['date'],
                    'value' => optional($e->$field)->format('Y-m-d'),
                ];
                break;

            case 'note':
                $meta += [
                    'type' => 'number',
                    'validation' => ['numeric', 'min:0', 'max:20'],
                    'value' => $e->note,
                ];
                break;

            case 'is_live_coding':
                $meta += [
                    'type' => 'boolean',
                    'validation' => ['boolean'],
                    'value' => (bool) $e->is_live_coding,
                ];
                break;

            case 'remarques_formateur':
            case 'remarques_apprenant':
            case 'remarque_evaluateur':
                $meta += [
                    'type' => 'text',
                    'validation' => ['string', 'nullable'],
                    'value' => $e->$field,
                ];
                break;

            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }

        return $meta;
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(RealisationTache $e, array $changes): RealisationTache
    {
        $allowed = $this->getFieldsEditable();
        $filtered = Arr::only($changes, $allowed);

        if (empty($filtered)) {
            abort(422, 'Aucun champ autorisé.');
        }

        $rules = [];
        foreach ($filtered as $field => $value) {
            $meta = $this->buildFieldMeta($e, $field);
            $rules[$field] = $meta['validation'] ?? ['nullable'];
        }

        // Validator::make($filtered, $rules)->validate();

        $e->fill($filtered);
        $e->save();
        $e->refresh();
        return $e;
    }

    /**
     * Formatte les valeurs pour l’affichage inline
     */
    public function formatDisplayValues(RealisationTache $e, array $fields): array
    {
      
        $out = [];
        foreach ($fields as $field) {
            switch ($field) {
                case 'etat_realisation_tache_id':
                    // $label = optional($e->etatRealisationTache)->libelle ?? '—';
                    // $code  = optional($e->etatRealisationTache->workflowTache)->code;
                    // $palette = ['TODO' => 'secondary', 'DOING' => 'warning', 'APPROVED' => 'success'];
                    // $out[$field] = [
                    //     'text'  => $label,
                    //     'badge' => $palette[$code] ?? 'secondary',
                    // ];
                     // ⚡ Utiliser un Blade partial pour produire le HTML final
                    $html = view('PkgRealisationTache::realisationTache.custom.fields.etatRealisationTache', [
                        'entity' => $e
                    ])->render();

                $out[$field] = [
                    'html' => $html, // rendu complet du partial
                ];
                    break;

                case 'dateDebut':
                case 'dateFin':
                    $out[$field] = ['text' => optional($e->$field)->format('Y-m-d') ?? '—'];
                    break;

                case 'note':
                    $out[$field] = ['text' => $e->note !== null ? $e->note . '/20' : '—'];
                    break;

                case 'is_live_coding':
                    $out[$field] = ['text' => $e->is_live_coding ? 'Oui' : 'Non'];
                    break;

                case 'remarques_formateur':
                case 'remarques_apprenant':
                case 'remarque_evaluateur':
                    $out[$field] = ['text' => (string) $e->$field ?? '—'];
                    break;

                default:
                    $out[$field] = ['text' => (string) data_get($e, $field, '—')];
            }
        }
        return $out;
    }










    public function prepareDataForIndexView(array $params = []): array
    {
        // On récupère les données du parent
        $baseData = parent::prepareDataForIndexView($params);

        /** @var \Illuminate\Support\Collection $realisationTaches */
        $realisationTaches = $baseData['realisationTaches_data'];

        // Charger toutes les révisions nécessaires en une seule requête
        $revisionsGrouped = RealisationTache::query()
            ->whereHas('etatRealisationTache.workflowTache', function ($q) {
                $q->where('code', 'REVISION_NECESSAIRE');
            })
            ->whereIn('realisation_projet_id', $realisationTaches->pluck('realisation_projet_id'))
            ->with(['tache', 'etatRealisationTache.workflowTache'])
            ->get()
            ->groupBy('realisation_projet_id');

        // Ajouter dans $baseData
        $baseData['revisionsBeforePriorityGrouped'] = $revisionsGrouped;

        // ⚡️ L'ajouter aussi dans le compact_value pour qu'il soit disponible dans Blade
        if (isset($baseData['realisationTache_compact_value']) && is_array($baseData['realisationTache_compact_value'])) {
            $baseData['realisationTache_compact_value']['revisionsBeforePriorityGrouped'] = $revisionsGrouped;
        }

        return $baseData;
    }





    /**
     * Summary of initFieldsFilterable
     * @return void
     */
    public function initFieldsFilterable()
    {

        $scopeVariables = $this->viewState->getScopeVariables('realisationTache');
        $this->fieldsFilterable = [];
        $sessionState = $this->sessionState;

        // Groupe 
        if(Auth::user()->hasRole(Role::ADMIN_ROLE) || !Auth::user()->hasAnyRole(Role::FORMATEUR_ROLE,Role::APPRENANT_ROLE) || !empty($this->viewState->get("filter.realisationTache.RealisationProjet.AffectationProjet.Groupe_id") ) ) {
            // Affichage de l'état de solicode
            $groupeService = new GroupeService();
            $groupes = $groupeService->all();
            $this->fieldsFilterable[] = $this->generateRelationFilter(
                __("PkgApprenants::Groupe.plural"), 
                'RealisationProjet.AffectationProjet.Groupe_id', 
                Groupe::class, 
                "code",
                "id",
                $groupes,
                "[name='RealisationProjet.Affectation_projet_id']",
                route('affectationProjets.getData'),
                "groupe_id"
            );
        }

        // AffectationProjet
        $affectationProjetService = new AffectationProjetService();
        $affectationProjets = match (true) {
            Auth::user()->hasRole(Role::FORMATEUR_ROLE) => $affectationProjetService->getAffectationProjetsByFormateurId($sessionState->get("formateur_id")),
            Auth::user()->hasRole(Role::APPRENANT_ROLE) => $affectationProjetService->getAffectationProjetsByApprenantId($sessionState->get("apprenant_id")),
            default => AffectationProjet::all(),
        };
        $this->fieldsFilterable[] = $this->generateRelationFilter(
            __("PkgRealisationProjets::affectationProjet.plural"), 
            'RealisationProjet.Affectation_projet_id', 
            AffectationProjet::class, 
            "id","id",
            $affectationProjets, 
             "[name='tache_id'],[name='etat_realisation_tache_id']",
            route('taches.getData') . "," . route('etatRealisationTaches.getData'),
             "projet.affectationProjets.id,formateur.projets.affectationProjets.id"
        );
       
        // --- ETAT REALISATION TACHE : choix selon AffectationProjet, Formateur ou Apprenant ---
        $affectationProjetId = $this->viewState->get(
            'filter.realisationTache.RealisationProjet.Affectation_projet_id'
        );

        $affectationProjetId = AffectationProjet::find($affectationProjetId) ? $affectationProjetId : null;
       
        $etatService = new EtatRealisationTacheService();

        if (!empty($affectationProjetId)) {
            // Cas 1 : AffectationProjet sélectionnée
            $affectationProjet = (new AffectationProjetService())->find($affectationProjetId);
            // Afficher les états de formateur pour ce projet
            $etats = $affectationProjet->projet->formateur->etatRealisationTaches;
        }
        elseif (Auth::user()->hasRole(Role::FORMATEUR_ROLE)) {
            // Cas 2 : Formateur sans projet sélectionné
            // Afficher les états génériques du formateur
            $etats = $etatService->getEtatRealisationTacheByFormateurId(
               $this->sessionState->get("formateur_id")
            );
        }
        else {
            // Cas 3 : Apprenant ou autre rôle
            // Aucun état formateur -> liste vide pour masquer le filtre
           $etats =  collect();
        }

        // Génération du filtre ManyToOne pour l'état de réalisation de tâche
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(
            __('PkgRealisationTache::etatRealisationTache.plural'),
            'etat_realisation_tache_id',
            EtatRealisationTache::class,
            'nom',
            $etats
        );
      
        // Affiche  WorkflowTache
        // Afficher si le filtre est selectionné
        // ou si le l'affectation de projet n'est pas selectionné et que l'acteur n'est pas formateur
        // dans ce cas il faut afficher WorkflowTache
        // --- WORKFLOW TACHE (état SoliCode) ---
        $workflowFilterCode = $this->viewState->get(
            'filter.realisationTache.etatRealisationTache.WorkflowTache.Code'
        );

      
        $workflowService = new WorkflowTacheService();
        $workflows = $workflowService->all();
    
        // Génération du filtre Relation pour WorkflowTache
        $this->fieldsFilterable[] = $this->generateRelationFilter(
                __('PkgRealisationTache::workflowTache.plural'),
                'etatRealisationTache.WorkflowTache.Code',
                \Modules\PkgRealisationTache\Models\WorkflowTache::class,
                'code',
                'code',
                $workflows
        );
        

        // Apprenant
        // TODO : Gapp add MetaData relationFilter
        $apprenants = match (true) {
            Auth::user()->hasRole(Role::FORMATEUR_ROLE) => (new FormateurService())->getApprenants($this->sessionState->get("formateur_id")),
            Auth::user()->hasRole(Role::APPRENANT_ROLE) => (new ApprenantService())->getApprenantsDeGroupe($this->sessionState->get("apprenant_id")),
            default => Apprenant::all(),
        };
        $this->fieldsFilterable[] = $this->generateRelationFilter(
            __("PkgApprenants::apprenant.plural"), 
            'RealisationProjet.Apprenant_id', 
            \Modules\PkgApprenants\Models\Apprenant::class,
            "id","id",
            $apprenants);

        // Tâches
        $tacheService = new TacheService();
        $taches = match (true) {
            Auth::user()->hasRole(Role::FORMATEUR_ROLE) => $tacheService->getTacheByFormateurId($sessionState->get("formateur_id")),
            Auth::user()->hasRole(Role::APPRENANT_ROLE) => $tacheService->getTacheByApprenantId($sessionState->get("apprenant_id")),
            default => Tache::all(),
        };
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(
            __("PkgCreationTache::tache.plural"),
            'tache_id',
            \Modules\PkgCreationTache\Models\Tache::class,
            'titre',
            $taches
        );
        

        
    }


    /**
     * Construit la requête pour récupérer les réalisations de tâches
     * en état "REVISION_NECESSAIRE" et priorité inférieure.
     *
     * @param  int  $realisationTacheId
     * @return Builder
     */
//    protected function revisionsBeforePriorityQuery(int $realisationTacheId): Builder
//     {
//         $current = RealisationTache::findOrFail($realisationTacheId);
//         $projectId = $current->realisation_projet_id;
//         $priorityOrdre = $current->tache->priorite;
//         if($priorityOrdre == null ) {
//             $priorityOrdre  = 0;
//         }
//         return RealisationTache::query()
//             ->where('realisation_projet_id', $projectId)
//             ->where('id', '<>', $realisationTacheId)
//             ->whereHas('etatRealisationTache.workflowTache', function(Builder $q) {
//                 $q->where('code', 'REVISION_NECESSAIRE');
//             })
//             ->whereHas('tache', function(Builder $q) use ($priorityOrdre) {
//                 $q->where('priorite', '<', $priorityOrdre);
//             });
//     }

    /**
     * Compte les réalisations avant priorité.
     *
     * @param  int  $realisationTacheId
     * @return int
     */
    // public function countRevisionsNecessairesBeforePriority(int $realisationTacheId): int
    // {
    //     return $this->revisionsBeforePriorityQuery($realisationTacheId)
    //                 ->count();
    // }

    /**
     * Récupère la liste des réalisations avant priorité.
     *
     * @param  int  $realisationTacheId
     * @return Collection<int, RealisationTache>
     */
    public function getRevisionsNecessairesBeforePriority(int $realisationTacheId): Collection
    {
        return $this->revisionsBeforePriorityQuery($realisationTacheId)
                    ->with(['tache', 'etatRealisationTache.workflowTache'])
                    ->get();
    }




}
