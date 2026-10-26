<?php

namespace Modules\PkgRealisationTache\Services\Traits\RealisationTache;

use Illuminate\Support\Facades\Auth;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationTache\Services\EtatRealisationTacheService;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgRealisationTache\Services\WorkflowTacheService;
use Modules\PkgRealisationTache\Models\WorkflowTache;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgCreationTache\Services\TacheService;
use Modules\PkgCreationTache\Models\Tache;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgCreationProjet\Models\EquipeProjet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Modules\PkgRealisationTache\Services\SeanceService;

trait RealisationTacheGetterTrait
{
    public const SITUATION_TRAVAIL = 'SITUATION_TRAVAIL';
    public const SITUATION_BLOCAGE = 'SITUATION_BLOCAGE';
    public const SITUATION_ATTENTE = 'SITUATION_ATTENTE';
    public const SITUATION_TERMINEE = 'SITUATION_TERMINEE';
    public const SITUATION_INACTIF_SEANCE = 'SITUATION_INACTIF_SEANCE';
    public const SITUATION_INACTIF_JOUR   = 'SITUATION_INACTIF_JOUR';
    public const SITUATION_CUSTOM = 'SITUATION_CUSTOM';

    public const SITUATION_WORKFLOW_MAP = [
        self::SITUATION_TRAVAIL => [
            'IN_PROGRESS', 
            'REVISION_NECESSAIRE', 
            'EN_REMEDIATION', 
            'IN_LIVE_CODING', 
            'PAUSED'
        ],
        self::SITUATION_BLOCAGE => [
            'EN_REMEDIATION', 
            'REVISION_NECESSAIRE', 
            'LATE', 
            'PAUSED'
        ],
        self::SITUATION_ATTENTE => [
            'TO_APPROVE', 
            'READY_FOR_LIVE_CODING', 
            'A_VALIDER_LIVE_CODING'
        ],
        self::SITUATION_TERMINEE => [
            'APPROVED',
            'NOT_VALIDATED', 
        ],
    ];

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

        // --- Nouveau : toutes les RT des mêmes projets (pour trouver la précédente par ordre-1)
        $previousTasksGrouped = RealisationTache::query()
            ->whereIn('realisation_projet_id', $realisationTaches->pluck('realisation_projet_id'))
            ->with([
                // on a besoin de l'ordre/priorité pour comparer
                'tache',
                'etatRealisationTache.workflowTache',
            ])
            ->get()
            ->groupBy('realisation_projet_id');

        $baseData['previousTasksGrouped'] = $previousTasksGrouped;

        // WorkflowTaches disponibles pour la vue
        $workflowService = new WorkflowTacheService();
        $workflowTachesList = $workflowService->all();
        $baseData['workflowTachesList'] = $workflowTachesList;

        // Données d'inactivité si situation d'inactivité active
        $situation = $this->viewState->get('filter.realisationTache.situation_pedagogique') ?? request('situation_pedagogique');
        if ($situation === self::SITUATION_INACTIF_SEANCE || $situation === self::SITUATION_INACTIF_JOUR) {
            $mode = ($situation === self::SITUATION_INACTIF_JOUR) ? 'jour' : 'seance';
            $inactiviteData = $this->getInactiviteData($mode);
            $baseData['inactiviteMetadata'] = $inactiviteData['metadata'];
            $baseData['inactiviteResume']   = $inactiviteData['resume'];
            $baseData['inactiviteSeanceNom'] = $inactiviteData['seance_nom'];
        } else {
            $baseData['inactiviteMetadata'] = [];
            $baseData['inactiviteResume']   = null;
            $baseData['inactiviteSeanceNom'] = null;
        }

        // Dispo dans Blade via compact_value
        if (isset($baseData['realisationTache_compact_value']) && is_array($baseData['realisationTache_compact_value'])) {
            $baseData['realisationTache_compact_value']['revisionsBeforePriorityGrouped'] = $revisionsGrouped;
            $baseData['realisationTache_compact_value']['previousTasksGrouped'] = $previousTasksGrouped;
            $baseData['realisationTache_compact_value']['workflowTachesList'] = $workflowTachesList;
            $baseData['realisationTache_compact_value']['inactiviteMetadata'] = $baseData['inactiviteMetadata'];
            $baseData['realisationTache_compact_value']['inactiviteResume']   = $baseData['inactiviteResume'];
            $baseData['realisationTache_compact_value']['inactiviteSeanceNom'] = $baseData['inactiviteSeanceNom'];
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

        $isAdmin = Auth::user()->hasRole(Role::ADMIN_ROLE) || !Auth::user()->hasAnyRole(Role::FORMATEUR_ROLE, Role::APPRENANT_ROLE);
        $isFormateur = Auth::user()->hasRole(Role::FORMATEUR_ROLE);
        
        $showFiliereFilter = $isAdmin;
        $showGroupeFilter = $isAdmin || !empty($this->viewState->get("filter.realisationTache.RealisationProjet.AffectationProjet.Groupe_id"));

        $formateurGroupes = collect();
        $formateurFilieres = collect();

        if ($isFormateur) {
            $formateur = \Modules\PkgFormation\Models\Formateur::with('groupes')->where('user_id', Auth::id())->first();
            if ($formateur) {
                $formateurGroupes = $formateur->groupes;
                $filieresIds = $formateurGroupes->pluck('filiere_id')->unique();
                $formateurFilieres = \Modules\PkgFormation\Models\Filiere::whereIn('id', $filieresIds)->get();
                
                if ($filieresIds->count() > 1) {
                    $showFiliereFilter = true;
                }
                if ($formateurGroupes->count() > 1) {
                    $showGroupeFilter = true;
                }
            }
        }

        // Filiere
        if ($showFiliereFilter) {
            $filiereService = new \Modules\PkgFormation\Services\FiliereService();
            $filieres = $isFormateur ? $formateurFilieres : $filiereService->all();
            
            $this->fieldsFilterable[] = $this->generateRelationFilter(
                __("PkgFormation::filiere.plural"),
                'RealisationProjet.AffectationProjet.Groupe.Filiere_id',
                \Modules\PkgFormation\Models\Filiere::class,
                "nom",
                "id",
                $filieres,
                "[name='RealisationProjet.AffectationProjet.Groupe_id']",
                route('groupes.getData'),
                "filiere_id"
            );
        }

        // Groupe 
        if ($showGroupeFilter) {
            $groupeService = new GroupeService();
            $groupesList = $isFormateur ? $formateurGroupes : $groupeService->all();
            $this->fieldsFilterable[] = $this->generateRelationFilter(
                __("PkgApprenants::groupe.plural"),
                'RealisationProjet.AffectationProjet.Groupe_id',
                Groupe::class,
                "code",
                "id",
                $groupesList,
                "[name='RealisationProjet.Affectation_projet_id'],[name='RealisationProjet.Apprenant_id']",
                route('affectationProjets.getData') . "," . route('apprenants.getData'),
                "groupe_id,groupe_id"
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
            "id",
            "id",
            $affectationProjets,
            "[name='tache_id'],[name='etat_realisation_tache_id'],[name='label_projet_id'],[name='tache.equipe_projet_id']",
            route('taches.getData') . "," . route('etatRealisationTaches.getData') . "," . route('labelProjets.getData') . "," . route('equipeProjets.getData'),
            "projet.affectationProjets.id,formateur.projets.affectationProjets.id,projet.affectationProjets.id,projet.affectationProjets.id"
        );

        // --- FILTRE EQUIPE PROJET ---
        $affectationProjetId = $this->viewState->get(
            'filter.realisationTache.RealisationProjet.Affectation_projet_id'
        );

        $equipeProjets = collect();
        if (!empty($affectationProjetId)) {
            $affectationProjet = $affectationProjetService->find($affectationProjetId);
            $projet = $affectationProjet?->projet;
            if ($projet) {
                $equipeProjets = $projet->equipeProjets;
            }
        }

        $filterEquipe = $this->generateRelationFilter(
            __("PkgCreationProjet::equipeProjet.singular"),
            'tache.equipe_projet_id',
            \Modules\PkgCreationProjet\Models\EquipeProjet::class,
            "nom",
            "id",
            $equipeProjets,
            "[name='tache_id'],[name='RealisationProjet.Apprenant_id']",
            route('taches.getData') . "," . route('apprenants.getData'),
            "equipe_projet_id,equipe_projet_id"
        );
        $filterEquipe['hidden'] = $equipeProjets->isEmpty(); // Proposé pour Gapp : cacher si vide
        $this->fieldsFilterable[] = $filterEquipe;

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
        } elseif (Auth::user()->hasRole(Role::FORMATEUR_ROLE)) {
            // Cas 2 : Formateur sans projet sélectionné
            // Afficher les états génériques du formateur
            $etats = $etatService->getEtatRealisationTacheByFormateurId(
                $this->sessionState->get("formateur_id")
            );
        } else {
            // Cas 3 : Apprenant ou autre rôle
            // Aucun état formateur -> liste vide pour masquer le filtre
            $etats = collect();
        }

        // --- FILTRE SITUATION PEDAGOGIQUE (Macro-situations & Personnalisée) ---
        $this->fieldsFilterable[] = [
            'label' => __('PkgRealisationTache::realisationTache.situation_pedagogique'),
            'field' => 'situation_pedagogique',
            'type' => 'ManyToOne',
            'options' => [
                ['id' => self::SITUATION_TRAVAIL, 'label' => '🔨 ' . __('PkgRealisationTache::realisationTache.situation_travail')],
                ['id' => self::SITUATION_BLOCAGE, 'label' => '⚠️ ' . __('PkgRealisationTache::realisationTache.situation_blocage')],
                ['id' => self::SITUATION_ATTENTE, 'label' => '⏳ ' . __('PkgRealisationTache::realisationTache.situation_attente')],
                ['id' => self::SITUATION_TERMINEE, 'label' => '✅ ' . __('PkgRealisationTache::realisationTache.situation_terminee')],
                ['id' => self::SITUATION_INACTIF_SEANCE, 'label' => '🚨 ' . __('PkgRealisationTache::realisationTache.situation_inactif_seance')],
                ['id' => self::SITUATION_INACTIF_JOUR, 'label' => '📅 ' . __('PkgRealisationTache::realisationTache.situation_inactif_jour')],
                ['id' => self::SITUATION_CUSTOM, 'label' => '🎯 ' . __('PkgRealisationTache::realisationTache.situation_custom')],
            ],
        ];

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
            WorkflowTache::class,
            'code',
            'code',
            $workflows
        );


        $equipeProjetId = $this->viewState->get('filter.realisationTache.tache.equipe_projet_id')
            ?? $this->viewState->get('filter.realisationTache.equipe_projet_id');

        // Apprenant
        // TODO : Gapp add MetaData relationFilter
        $apprenants = match (true) {
            Auth::user()->hasRole(Role::FORMATEUR_ROLE) => (new FormateurService())->getApprenants($this->sessionState->get("formateur_id")),
            Auth::user()->hasRole(Role::APPRENANT_ROLE) => (new ApprenantService())->getApprenantsDeGroupe($this->sessionState->get("apprenant_id")),
            default => Apprenant::all(),
        };

        if (!empty($equipeProjetId)) {
            $equipe = EquipeProjet::with('apprenants')->find($equipeProjetId);
            if ($equipe) {
                $apprenants = $equipe->apprenants;
            }
        }

        $this->fieldsFilterable[] = $this->generateRelationFilter(
            __("PkgApprenants::apprenant.plural"),
            'RealisationProjet.Apprenant_id',
            Apprenant::class,
            "id",
            "id",
            $apprenants
        );

        // Tâches
        $tacheService = new TacheService();
        $taches = match (true) {
            Auth::user()->hasRole(Role::FORMATEUR_ROLE) => $tacheService->getTacheByFormateurId($sessionState->get("formateur_id")),
            Auth::user()->hasRole(Role::APPRENANT_ROLE) => $tacheService->getTacheByApprenantId($sessionState->get("apprenant_id")),
            default => Tache::all(),
        };

        if (!empty($equipeProjetId)) {
            $taches = collect($taches)->filter(function($tache) use ($equipeProjetId) {
                return (string) $tache->equipe_projet_id === (string) $equipeProjetId
                    || is_null($tache->equipe_projet_id);
            });
        }
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(
            __("PkgCreationTache::tache.plural"),
            'tache_id',
            Tache::class,
            'titre',
            $taches
        );


        // TODO : Il générer une exception "<" : Problème de falback ajax, il retourne une page avec etteur de getData
        //  if (!array_key_exists('labelProjets', $scopeVariables)) {

        //             $labelProjetService = new \Modules\PkgCreationProjet\Services\LabelProjetService();
        //             $labelProjetIds = $this->getAvailableFilterValues('labelProjets.id');
        //             $labelProjets = $labelProjetService->getByIds($labelProjetIds);

        //             $this->fieldsFilterable[] = $this->generateManyToManyFilter(
        //                 __("PkgCreationProjet::labelProjet.plural"), 
        //                 'label_projet_id', 
        //                 \Modules\PkgCreationProjet\Models\LabelProjet::class, 
        //                 'nom',
        //                 $labelProjets
        //             );
        //     }


    }

    /**
     * Construit la requête pour récupérer les réalisations de tâches
     * en état "REVISION_NECESSAIRE" et priorité inférieure.
     *
     * @param  int  $realisationTacheId
     * @return Builder
     */
    protected function revisionsBeforePriorityQuery(int $realisationTacheId): Builder
    {
        $current = RealisationTache::findOrFail($realisationTacheId);
        $projectId = $current->realisation_projet_id;
        $priorityOrdre = $current->tache->priorite;
        if ($priorityOrdre == null) {
            $priorityOrdre = 0;
        }
        return RealisationTache::query()
            ->where('realisation_projet_id', $projectId)
            ->where('id', '<>', $realisationTacheId)
            ->whereHas('etatRealisationTache.workflowTache', function (Builder $q) {
                $q->where('code', 'REVISION_NECESSAIRE');
            })
            ->whereHas('tache', function (Builder $q) use ($priorityOrdre) {
                $q->where('priorite', '<', $priorityOrdre);
            });
    }

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
    /**
     * Trie pardéfaut
     * 1️⃣ Trier par date de fin de l'affectation
     * 2️⃣ Ensuite par ordre de tâche
     * @param mixed $query
     */
    public function defaultSort($query)
    {
        return $query
            // ->with(['realisationProjet.affectationProjet']) // Charger affectationProjet
            ->join('realisation_projets', 'realisation_taches.realisation_projet_id', '=', 'realisation_projets.id')
            ->join('affectation_projets', 'realisation_projets.affectation_projet_id', '=', 'affectation_projets.id')
            ->join('taches', 'realisation_taches.tache_id', '=', 'taches.id')
            ->orderBy('affectation_projets.date_fin', 'desc') // 1️⃣ Trier par date de fin de l'affectation
            ->orderBy('taches.ordre', 'asc') // 2️⃣ Ensuite par ordre de tâche
            ->select('realisation_taches.*'); // 🎯 Important pour éviter le problème de Model::hydrate
    }
    /**
     * Vérifie l'existence d'une réalisation de tâche pour une tâche et une réalisation de projet données.
     *
     * @param int $tacheId
     * @param int $realisationProjetId
     * @return bool
     */
    public function existsForTacheAndProject(int $tacheId, int $realisationProjetId): bool
    {
        return $this->allQuery()->where([
            'tache_id' => $tacheId,
            'realisation_projet_id' => $realisationProjetId
        ])->exists();
    }

    /**
     * Applique les filtres sur la requête RealisationTache.
     * Surcharge le comportement standard pour le filtre par équipe afin d'inclure
     * à la fois les tâches spécifiques à l'équipe et les tâches communes du projet
     * réalisées par les membres de cette équipe.
     *
     * @param Builder $builder
     * @param mixed $model
     * @param array $filterVariables
     * @return void
     */
    public function filter(Builder $builder, $model, array $filterVariables): void
    {
        $equipeProjetId = $filterVariables['tache.equipe_projet_id'] ?? $filterVariables['equipe_projet_id'] ?? null;
        if (!empty($equipeProjetId)) {
            unset($filterVariables['tache.equipe_projet_id'], $filterVariables['equipe_projet_id']);

            $equipe = EquipeProjet::with('apprenants')->find($equipeProjetId);
            $apprenantIds = $equipe ? $equipe->apprenants->pluck('id')->toArray() : [];

            if (!empty($apprenantIds)) {
                $builder->where(function (Builder $query) use ($equipeProjetId, $apprenantIds) {
                    // Uniquement les réalisations des apprenants membres de l'équipe
                    $query->whereHas('realisationProjet', function ($rp) use ($apprenantIds) {
                        $rp->whereIn('apprenant_id', $apprenantIds);
                    })
                    // Tâches spécifiques de l'équipe ET tâches communes (sans équipe assignée)
                    ->whereHas('tache', function ($q) use ($equipeProjetId) {
                        $q->where(function ($sq) use ($equipeProjetId) {
                            $sq->where('equipe_projet_id', $equipeProjetId)
                               ->orWhereNull('equipe_projet_id');
                        });
                    });
                });
            } else {
                $builder->whereRaw('1 = 0');
            }
        }

        // --- FILTRE SITUATION PEDAGOGIQUE ET ETATS PERSONNALISES ---
        $situation = $filterVariables['situation_pedagogique'] 
            ?? $this->viewState->get('filter.realisationTache.situation_pedagogique')
            ?? request('situation_pedagogique');

        $customEtats = $filterVariables['custom_etats'] 
            ?? $this->viewState->get('filter.realisationTache.custom_etats')
            ?? request('custom_etats');

        unset($filterVariables['situation_pedagogique'], $filterVariables['custom_etats']);

        if (!empty($situation) && isset(self::SITUATION_WORKFLOW_MAP[$situation])) {
            $codes = self::SITUATION_WORKFLOW_MAP[$situation];
            $builder->whereHas('etatRealisationTache.workflowTache', function (Builder $q) use ($codes) {
                $q->whereIn('code', $codes);
            });
            // Pour ne pas entrer en conflit avec une ancienne sélection d'état unitaire
            unset($filterVariables['etat_realisation_tache_id']);
            unset($filterVariables['etatRealisationTache.WorkflowTache.Code']);
        } elseif ($situation === self::SITUATION_INACTIF_SEANCE || $situation === self::SITUATION_INACTIF_JOUR) {
            $mode = ($situation === self::SITUATION_INACTIF_JOUR) ? 'jour' : 'seance';
            $inactiviteData = $this->getInactiviteData($mode);
            $targetIds = $inactiviteData['target_tache_ids'];

            if (!empty($targetIds)) {
                $builder->whereIn('realisation_taches.id', $targetIds);
                $idsOrdered = implode(',', $targetIds);
                $builder->orderByRaw("FIELD(realisation_taches.id, {$idsOrdered})");
            } else {
                // Aucun apprenant inactif trouvé
                $builder->whereRaw('1 = 0');
            }

            unset($filterVariables['etat_realisation_tache_id']);
            unset($filterVariables['etatRealisationTache.WorkflowTache.Code']);
        } elseif ($situation === self::SITUATION_CUSTOM && !empty($customEtats)) {
            $customEtatsArray = is_array($customEtats) ? $customEtats : explode(',', (string) $customEtats);
            $customEtatsArray = array_values(array_filter(array_map('trim', $customEtatsArray)));

            if (!empty($customEtatsArray)) {
                $isNumeric = is_numeric($customEtatsArray[0]);
                if ($isNumeric) {
                    $builder->whereIn('realisation_taches.etat_realisation_tache_id', $customEtatsArray);
                } else {
                    $builder->whereHas('etatRealisationTache.workflowTache', function (Builder $q) use ($customEtatsArray) {
                        $q->whereIn('code', $customEtatsArray);
                    });
                }
                unset($filterVariables['etat_realisation_tache_id']);
                unset($filterVariables['etatRealisationTache.WorkflowTache.Code']);
            }
        }

        parent::filter($builder, $model, $filterVariables);
    }

    /**
     * Analyse et détecte les apprenants inactifs durant la séance en cours ou la journée,
     * et identifie pour chacun leur tâche de travail prioritaire avec métadonnées d'inactivité.
     *
     * @param string $mode 'seance' ou 'jour'
     * @return array{target_tache_ids: int[], metadata: array, resume: string, seance_nom: string, nb_inactifs: int}
     */
    public function getInactiviteData(string $mode = 'seance'): array
    {
        $seanceService = new SeanceService();

        if ($mode === 'jour') {
            [$start, $end] = $seanceService->getTodayTimeRange();
            $seanceNom = __('Journée en cours') . ' (' . __('depuis') . ' ' . $start->format('H:i') . ')';
        } else {
            $seance = $seanceService->getLastOrCurrentSeance();
            [$start, $end] = $seanceService->getSeanceTimeRange($seance);
            $seanceNom = $seance['nom'];
        }

        // 1. Apprenants ayant enregistré au moins un changement d'état sur la période
        $activeApprenantIds = \Modules\PkgRealisationTache\Models\HistoriqueRealisationTache::query()
            ->whereBetween('dateModification', [$start, $end])
            ->join('realisation_taches', 'historique_realisation_taches.realisation_tache_id', '=', 'realisation_taches.id')
            ->join('realisation_projets', 'realisation_taches.realisation_projet_id', '=', 'realisation_projets.id')
            ->pluck('realisation_projets.apprenant_id')
            ->unique()
            ->toArray();

        // 2. Périmètre des apprenants supervisés selon le rôle et les filtres actifs
        $apprenants = match (true) {
            Auth::user()->hasRole(Role::FORMATEUR_ROLE) => (new FormateurService())->getApprenants($this->sessionState->get("formateur_id")),
            Auth::user()->hasRole(Role::APPRENANT_ROLE) => (new ApprenantService())->getApprenantsDeGroupe($this->sessionState->get("apprenant_id")),
            default => Apprenant::all(),
        };

        $supervisedApprenantIds = $apprenants->pluck('id')->toArray();
        if (empty($supervisedApprenantIds)) {
            $supervisedApprenantIds = \Modules\PkgRealisationProjets\Models\RealisationProjet::query()
                ->pluck('apprenant_id')
                ->unique()
                ->toArray();
        }

        // 3. Déduction des apprenants inactifs
        $inactiveApprenantIds = array_values(array_diff($supervisedApprenantIds, $activeApprenantIds));

        $targetTaches = [];
        $metadataByTacheId = [];
        $now = Carbon::now();

        foreach ($inactiveApprenantIds as $apprenantId) {
            // Priorité 1 : Tâche en cours de travail
            $tache = RealisationTache::query()
                ->whereHas('realisationProjet', function ($q) use ($apprenantId) {
                    $q->where('apprenant_id', $apprenantId);
                })
                ->whereHas('etatRealisationTache.workflowTache', function ($q) {
                    $q->whereIn('code', ['IN_PROGRESS', 'REVISION_NECESSAIRE', 'EN_REMEDIATION', 'PAUSED']);
                })
                ->first();

            // Priorité 2 : Première tâche TODO à faire
            if (!$tache) {
                $tache = RealisationTache::query()
                    ->whereHas('realisationProjet', function ($q) use ($apprenantId) {
                        $q->where('apprenant_id', $apprenantId);
                    })
                    ->whereHas('etatRealisationTache.workflowTache', function ($q) {
                        $q->where('code', 'TODO');
                    })
                    ->join('taches', 'realisation_taches.tache_id', '=', 'taches.id')
                    ->orderBy('taches.ordre', 'asc')
                    ->select('realisation_taches.*')
                    ->first();
            }

            // Priorité 3 : Dernière tâche modifiée
            if (!$tache) {
                $tache = RealisationTache::query()
                    ->whereHas('realisationProjet', function ($q) use ($apprenantId) {
                        $q->where('apprenant_id', $apprenantId);
                    })
                    ->latest('updated_at')
                    ->first();
            }

            if ($tache) {
                // Recherche de la toute dernière entrée d'historique de l'apprenant
                $dernierHistorique = \Modules\PkgRealisationTache\Models\HistoriqueRealisationTache::query()
                    ->join('realisation_taches', 'historique_realisation_taches.realisation_tache_id', '=', 'realisation_taches.id')
                    ->join('realisation_projets', 'realisation_taches.realisation_projet_id', '=', 'realisation_projets.id')
                    ->where('realisation_projets.apprenant_id', $apprenantId)
                    ->latest('historique_realisation_taches.dateModification')
                    ->select('historique_realisation_taches.*')
                    ->first();

                if ($dernierHistorique && $dernierHistorique->dateModification) {
                    $derniereDate = Carbon::parse($dernierHistorique->dateModification);
                    $dureeMinutes = max(0, (int) $derniereDate->diffInMinutes($now));
                    $dernierChangementStr = $derniereDate->isToday() 
                        ? $derniereDate->format('H:i') 
                        : $derniereDate->format('d/m H:i');
                } else {
                    $dureeMinutes = max(0, (int) $start->diffInMinutes($now));
                    $dernierChangementStr = __('Aucun événement');
                }

                $gravite = ($dureeMinutes >= 75) ? 'danger' : (($dureeMinutes >= 30) ? 'warning' : 'info');

                $targetTaches[$tache->id] = $dureeMinutes;
                $metadataByTacheId[$tache->id] = [
                    'duree_minutes' => $dureeMinutes,
                    'duree_formatee' => $seanceService->formatDuree($dureeMinutes),
                    'dernier_changement' => $dernierChangementStr,
                    'seance_nom' => $seanceNom,
                    'gravite' => $gravite,
                    'apprenant_id' => $apprenantId,
                ];
            }
        }

        // Tri décroissant par durée d'inactivité
        arsort($targetTaches);
        $sortedTacheIds = array_keys($targetTaches);
        $nbInactifs = count($sortedTacheIds);

        $resume = trans_choice(
            '{0} Aucun apprenant inactif|{1} :count apprenant inactif détecté (:seance)|[2,*] :count apprenants inactifs détectés (:seance)',
            $nbInactifs,
            ['count' => $nbInactifs, 'seance' => $seanceNom]
        );

        return [
            'target_tache_ids' => $sortedTacheIds,
            'metadata' => $metadataByTacheId,
            'resume' => $resume,
            'seance_nom' => $seanceNom,
            'nb_inactifs' => $nbInactifs,
        ];
    }
}
