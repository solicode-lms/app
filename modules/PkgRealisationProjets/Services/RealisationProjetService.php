<?php


namespace Modules\PkgRealisationProjets\Services;

use Exception;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgRealisationTache\Services\EtatRealisationTacheService;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationProjets\Services\Base\BaseRealisationProjetService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\PkgRealisationProjets\Models\EtatsRealisationProjet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgApprentissage\Models\EtatRealisationMicroCompetence;
use Modules\PkgApprentissage\Models\RealisationChapitre;
use Modules\PkgApprentissage\Models\RealisationMicroCompetence;
use Modules\PkgApprentissage\Models\RealisationUa;
use Modules\PkgApprentissage\Services\RealisationChapitreService;
use Modules\PkgApprentissage\Services\RealisationMicroCompetenceService;
use Modules\PkgApprentissage\Services\RealisationUaProjetService;
use Modules\PkgApprentissage\Services\RealisationUaPrototypeService;
use Modules\PkgApprentissage\Services\RealisationUaService;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgCreationTache\Models\Tache;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Modules\PkgNotification\Enums\NotificationType;
use Modules\PkgRealisationProjets\Models\WorkflowProjet;

/**
 * 
 * Classe RealisationProjetService pour gérer la persistance de l'entité RealisationProjet.
 */
class RealisationProjetService extends BaseRealisationProjetService
{
     protected array $index_with_relations = [
        'affectationProjet',
        'affectationProjet.projet',
        'affectationProjet.projet.livrables',
        'apprenant',
        'livrablesRealisations',
        'etatsRealisationProjet',
    ];

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
        $etatsRealisationProjetService = new \Modules\PkgRealisationProjets\Services\EtatsRealisationProjetService();
        $etatsRealisationProjetIds = $this->getAvailableFilterValues('etats_realisation_projet_id');
        $etatsRealisationProjets = $etatsRealisationProjetService->getByIds($etatsRealisationProjetIds);

        $this->fieldsFilterable[] = $this->generateManyToOneFilter(
            __("PkgRealisationProjets::etatsRealisationProjet.plural"), 
            'etats_realisation_projet_id', 
            \Modules\PkgRealisationProjets\Models\EtatsRealisationProjet::class, 
            'code',
            $etatsRealisationProjets
        );


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


            $query->with(array_unique($this->index_with_relations));

            // Calcul du nombre total des résultats filtrés
            $this->totalFilteredCount = $query->count();

            return $query->paginate($perPage, $columns);
        });


       
    }
    

      public function deletedObserverJob(int $id, string $token): void
    {
        $jobManager = new JobManager($token); 
        $payload = $jobManager->getPayload();

        $uaIds = collect($payload['ua_ids'] ?? []);
        $realisation_chapitres_ids = collect($payload['realisation_chapitres_ids'] ?? []);

        $total = 0;

        // 1️⃣ Chapitres (N1)
        if ($realisation_chapitres_ids->isNotEmpty()) {
            $total++;
        }

        // 2️⃣ UA (N2 / N3)
        $total += $uaIds->count();

        $jobManager->initProgress($total);

        // 1️⃣ Chapitre (N1)
        if ($realisation_chapitres_ids->isNotEmpty()) {
            $jobManager->setLabel("Mise à jour des chapitres");
            $realisationChapitreService = new RealisationChapitreService();
            $realisationChapitreService->calculerProgressionDepuisRealisationChapitresIds($realisation_chapitres_ids);
            $jobManager->tick();
        }

        // 2️⃣ Unités d'apprentissage (UA)
        if ($uaIds->isNotEmpty()) {
            $realisationUaService = new RealisationUaService();
            $uas = RealisationUa::whereIn('id', $uaIds)->get();
            foreach ($uas as $ua) {
                $jobManager->setLabel("Calcul progression pour UA #{$ua}");
                $realisationUaService->calculerProgression($ua);
                $jobManager->tick();
            }
        }

        $jobManager->finish();
    }

    /**
     * Règles métiers appliquées avant la mise à jour d'un RealisationProjet.
     *
     * @param array $data Données à mettre à jour (passées par référence).
     * @param int $id Identifiant de l'entité à modifier.
     * @return void
     * @throws ValidationException En cas de violation de règles métier.
     */
    public function beforeUpdateRules(array &$data, int $id): void
    {
        $entity = $this->find($id);

        if (empty($entity)) {
            throw ValidationException::withMessages([
                'id' => "Projet de réalisation introuvable."
            ]);
        }

        // 🛡️ Vérification de changement d'état
        if (!empty($data["etats_realisation_projet_id"])) {
            $nouvelEtatId = $data["etats_realisation_projet_id"];

            $etatActuel = $entity->etatsRealisationProjet;

            // Charger le nouvel état pour validation
            $nouvelEtat = EtatsRealisationProjet::find($nouvelEtatId);

            if (!$nouvelEtat) {
                throw ValidationException::withMessages([
                    'etats_realisation_projet_id' => "L'état sélectionné est invalide."
                ]);
            }

            // 🛡️ 1. Empêcher la modification d'un état actuel protégé
            if ($etatActuel) {
                if (
                    $etatActuel->is_editable_by_formateur
                    && $etatActuel->id !== $nouvelEtatId
                    && !Auth::user()->hasRole(Role::FORMATEUR_ROLE)
                ) {
                    throw ValidationException::withMessages([
                        'etats_realisation_projet_id' => "L'état actuel du projet ne peut être changé que par un formateur."
                    ]);
                }
            }

            // 🛡️ 2. Empêcher l'affectation d'un nouvel état protégé
            if (
                $nouvelEtat->is_editable_by_formateur
                && !Auth::user()->hasRole(Role::FORMATEUR_ROLE)
            ) {
                throw ValidationException::withMessages([
                    'etats_realisation_projet_id' => "Vous ne pouvez pas affecter cet état réservé au formateur."
                ]);
            }
        }

        // 🛡️ 3. Vérification cohérence dates (facultatif mais recommandé)
        if (isset($data['date_debut'], $data['date_fin']) && $data['date_debut'] > $data['date_fin']) {
            throw ValidationException::withMessages([
                'date_fin' => "La date de fin doit être postérieure à la date de début."
            ]);
        }
    }

    public function afterCreateRules($realisationProjet): void
    {
        if (!$realisationProjet instanceof RealisationProjet) {
            return; // 🛡️ Vérification de sécurité
        }
         // Étape 1 : Affecter l'état "TODO" s'il existe
        if (empty($realisationProjet->etats_realisation_projet_id)) {
            $etatTodo = EtatsRealisationProjet::where('code', 'TODO')->first();

            if ($etatTodo) {
                $realisationProjet->etats_realisation_projet_id = $etatTodo->id;
                $realisationProjet->save();
            }
        }
        // Étape 2 : Notification
        $this->notifierApprenant($realisationProjet);

        // Étape 3 : Création des RealisationTache
        $this->creerRealisationTaches($realisationProjet);
    }

    /**
     * Envoie une notification à l'apprenant assigné au projet.
     *
     * @param \Modules\PkgRealisationProjets\Models\RealisationProjet $realisationProjet
     * @return void
     */
    private function notifierApprenant(RealisationProjet $realisationProjet): void
    {
        $apprenant = $realisationProjet->apprenant;

        if ($apprenant && $apprenant->user) {
            /** @var \Modules\PkgNotification\Services\NotificationService $notificationService */
            $notificationService = app(\Modules\PkgNotification\Services\NotificationService::class);

            $notificationService->sendNotification(
                userId: $apprenant->user->id,
                title: 'Nouveau Projet de Réalisation Assigné',
                message: "Vous avez été assigné à un nouveau projet de réalisation. Consultez votre espace projets.",
                data: [
                    'lien' => route('realisationProjets.index', [
                        'contextKey' => 'realisationProjet.index',
                        'action' => 'show',
                        'id' => $realisationProjet->id
                    ]),
                    'realisationProjet' => $realisationProjet->id
                ],
                type: NotificationType::NOUVEAU_PROJET->value
            );
        }
    }

    private function creerRealisationTaches(RealisationProjet $realisationProjet): void
    {
        $formateur_id = $realisationProjet->affectationProjet->projet->formateur_id;
        $affectationProjet = $realisationProjet->affectationProjet;
        $taches = $affectationProjet->projet->taches;
        $mobilisationUas = $affectationProjet->projet->mobilisationUas ?? collect();

        $etatInitialRealisationTache = $formateur_id
            ? (new EtatRealisationTacheService())->getDefaultEtatByFormateurId($formateur_id)
            : null;

        $realisationTacheService = new RealisationTacheService();
        $realisationUaService = new RealisationUaService();

        $realisationChapitreService = app(RealisationChapitreService::class);
        $realisationUaProjetService = app(RealisationUaProjetService::class);
        $realisationUaPrototypeService = app(RealisationUaPrototypeService::class);


        foreach ($taches as $tache) {
            $tacheAffectation = $tache->tacheAffectations
                ->where('affectation_projet_id', $affectationProjet->id)
                ->first();

            // TODO : il faut ajouter réalisationTace si la tâche n'a pas de RéaisationTace
            // Le cas ou l'apprenant modifer les état en dehor de réalisation des tâches

            
            // ⚠️ Si la tâche est liée à un chapitre terminé, on passe à la suivante
            if ($tache->chapitre) {
                $realisationUA = $realisationUaService->getOrCreateApprenant(
                    $realisationProjet->apprenant_id,
                    $tache->chapitre->unite_apprentissage_id
                );

                $chapitreExistant = RealisationChapitre::where('chapitre_id', $tache->chapitre->id)
                    ->where('realisation_ua_id', $realisationUA->id)
                    ->first();

                if ($chapitreExistant && $chapitreExistant->etatRealisationChapitre?->code === 'DONE') {
                    // 🚫 Ne pas créer de RealisationTache pour ce chapitre
                    continue;
                }
            }

            // ✅ Création de la RealisationTache (si non bloquée)
            $realisationTache = $realisationTacheService->create([
                'realisation_projet_id' => $realisationProjet->id,
                'tache_id' => $tache->id,
                'etat_realisation_tache_id' => $etatInitialRealisationTache?->id,
                'tache_affectation_id' => $tacheAffectation?->id,
            ]);

            // 🔗 Si le chapitre existe, on lie ou crée sa RealisationChapitre
            if ($tache->chapitre) {
                if (isset($chapitreExistant)) {
                    // Si le chapitre existe et n’est pas DONE, on met à jour le lien
                    if ($chapitreExistant->etatRealisationChapitre?->code !== 'DONE') {
                        $chapitreExistant->update([
                            'realisation_tache_id' => $realisationTache->id,
                        ]);
                    }
                } else {
                    // Sinon, on crée une nouvelle RealisationChapitre
                    $realisationChapitreService->create([
                        'realisation_tache_id' => $realisationTache->id,
                        'chapitre_id' => $tache->chapitre->id,
                        'realisation_ua_id' => $realisationUA->id,
                    ]);
                }
            }

            
            // TODO : ce traitement doit être réaliser aussi aprés l'insertion ou modification 
            //  d'une mobilisation d'unité d'apprentissage dans le projet 

            // 🧩 Gestion des UA prototypes (N2)
            if ($tache->phaseEvaluation?->code == "N2") {
                foreach ($mobilisationUas as $mobilisation) {
                    $realisationUA = $realisationUaService->getOrCreateApprenant(
                        $realisationProjet->apprenant_id,
                        $mobilisation->unite_apprentissage_id
                    );

                    $realisationUaPrototypeService->create([
                        'realisation_tache_id' => $realisationTache->id,
                        'realisation_ua_id' => $realisationUA->id,
                        'bareme' => $mobilisation->bareme_evaluation_prototype ?? 0,
                    ]);
                }
            }

            // 🧩 Gestion des UA projets (N3)
            if ($tache->phaseEvaluation?->code == "N3") {
                foreach ($mobilisationUas as $mobilisation) {
                    $realisationUA = $realisationUaService->getOrCreateApprenant(
                        $realisationProjet->apprenant_id,
                        $mobilisation->unite_apprentissage_id
                    );

                    $realisationUaProjetService->create([
                        'realisation_tache_id' => $realisationTache->id,
                        'realisation_ua_id' => $realisationUA->id,
                        'bareme' => $mobilisation->bareme_evaluation_projet ?? 0,
                    ]);
                }
            }
        }
    }


    private function getOrCreateRealisationUa(int $uniteApprentissageId, int $realisationMicroCompetenceId): int
    {
        return RealisationUa::firstOrCreate([
            'unite_apprentissage_id' => $uniteApprentissageId,
            'realisation_micro_competence_id' => $realisationMicroCompetenceId,
        ])->id;
    }

    public function syncApprenantsAvecRealisationProjets($affectationProjet, $nouveauxApprenants)
    {
        $apprenantsExistants = $affectationProjet->realisationProjets->pluck('apprenant_id');

        $apprenantsAJouter = $nouveauxApprenants->whereNotIn('id', $apprenantsExistants);
        $apprenantsASupprimer = $apprenantsExistants->diff($nouveauxApprenants->pluck('id'));

        // Suppression des réalisations obsolètes
        if ($apprenantsASupprimer->isNotEmpty()) {
            $this->model->query()
                ->where('affectation_projet_id', $affectationProjet->id)
                ->whereIn('apprenant_id', $apprenantsASupprimer)
                ->delete();
        }

        // Ajout des nouvelles réalisations
        foreach ($apprenantsAJouter as $apprenant) {
            $this->create([
                'apprenant_id' => $apprenant->id,
                'affectation_projet_id' => $affectationProjet->id,
                'date_debut' => $affectationProjet->date_debut,
                'date_fin' => $affectationProjet->date_fin,
                'rapport' => null,
                'etats_realisation_projet_id' => null,
            ]);
        }
    }

    /**
     * Met à jour dynamiquement l'état du projet selon l'état de ses tâches.
     *
     * @param RealisationProjet $realisationProjet
     * @return void
     */
    public function mettreAJourEtatDepuisRealisationTaches(RealisationProjet $realisationProjet): void
    {
        if (!$realisationProjet instanceof RealisationProjet) {
            return;
        }

        $realisationProjet->loadMissing('realisationTaches.etatRealisationTache.workflowTache');

        $codesTaches = $realisationProjet->realisationTaches
            ->pluck('etatRealisationTache.workflowTache.code')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        if (empty($codesTaches)) {
            return;
        }

        $nouvelEtatCode = null;

        // ✅ DONE : toutes les tâches sont DONE
        if (collect($codesTaches)->every(fn($code) => $code === 'APPROVED')) {
            $nouvelEtatCode = 'DONE';

        // ✅ TO_APPROVE : toutes les tâches sont TO_APPROVE ou DONE
        } elseif (collect($codesTaches)->every(fn($code) => in_array($code, ['TO_APPROVE', 'APPROVED']))) {
            $nouvelEtatCode = 'TO_APPROVE';

        // ✅ PAUSED : toutes les tâches sont PAUSED
        } elseif (collect($codesTaches)->every(fn($code) => $code === 'PAUSED')) {
            $nouvelEtatCode = 'PAUSED';

        } elseif (collect($codesTaches)->every(fn($code) => $code === 'TODO')) {
            $nouvelEtatCode = 'TODO';

        // ✅ IN_PROGRESS : au moins une tâche est IN_PROGRESS
        } else {
            $nouvelEtatCode = 'IN_PROGRESS';
        }

        // Appliquer l’état si différent
        if ($nouvelEtatCode) {
            $etat = EtatsRealisationProjet::where('code', $nouvelEtatCode)->first();

            if ($etat && $realisationProjet->etats_realisation_projet_id !== $etat->id) {
                $realisationProjet->etats_realisation_projet_id = $etat->id;
                $realisationProjet->save();
            }
        }
    }


    /**
     * Met à jour les champs `progression_execution_cache` et `progression_validation_cache`
     * du RealisationProjet à partir des états des tâches associées.
     *
     * 🔁 Règle métier :
     * - `progression_execution_cache` : pourcentage de tâches arrivées à un état "finalisable"
     *   (actuellement : NOT_VALIDATED ou APPROVED).
     * - `progression_validation_cache` : pourcentage de tâches validées pédagogiquement (APPROVED uniquement).
     *
     * Les états sont calculés à partir des `workflowTache.code` liés aux `etatRealisationTache`
     * de chaque tâche du projet concerné.
     *
     * Si aucune tâche n’est associée au projet, les deux progressions sont mises à zéro.
     *
     * @param RealisationProjet $projet Le projet à analyser.
     * @return void
     */
    public function mettreAJourProgressionDepuisEtatDesTaches(RealisationProjet $projet): void
    {
        $realisationTaches = $projet->realisationTaches;

        if ($realisationTaches->isEmpty()) {
            $projet->update([
                'progression_execution_cache' => 0,
                'progression_validation_cache' => 0,
            ]);
            return;
        }

        $total = $realisationTaches->count();

        // États d'exécution (entre IN_PROGRESS et LIVE_CODING inclus)
        $executionCodes = ['NOT_VALIDATED', 'APPROVED'];

        // États de validation (approuvés uniquement)
        $validationCodes = ['APPROVED'];

        $executionCount = $realisationTaches->filter(function ($tache) use ($executionCodes) {
            return in_array(optional($tache->etatRealisationTache->workflowTache)->code, $executionCodes);
        })->count();

        $validationCount = $realisationTaches->filter(function ($tache) use ($validationCodes) {
            return in_array(optional($tache->etatRealisationTache->workflowTache)->code, $validationCodes);
        })->count();

        $projet->update([
            'progression_execution_cache' => round(($executionCount / $total) * 100, 2),
            'progression_validation_cache' => round(($validationCount / $total) * 100, 2),
        ]);
    }


    /**
     * Calcule et met à jour la note totale (`note_cache`) et le barème (`bareme_cache`)
     * du projet à partir des tâches notées uniquement.
     *
     * 🧠 Règles métier :
     * - note_cache : somme des `note` des tâches du projet.
     * - bareme_cache : somme des `bareme` uniquement pour les tâches qui ont une `note` non nulle.
     *
     * @param RealisationProjet $projet
     * @return void
     */
    public function calculerNoteEtBaremeDepuisTaches(RealisationProjet $projet): void
    {
        $realisationTaches = $projet->realisationTaches;

        if ($realisationTaches->isEmpty()) {
            $projet->update([
                'note_cache' => 0,
                'bareme_cache' => 0,
            ]);
            return;
        }

        $noteTotale = 0;
        $baremeTotal = 0;

        foreach ($realisationTaches as $tache) {
            if (!is_null($tache->note)) {
                $noteTotale += $tache->note;
                $baremeTotal += $tache->tache->note ?? 0;
            }
        }

        $projet->update([
            'note_cache' => round($noteTotale, 2),
            'bareme_cache' => round($baremeTotal, 2),
        ]);
    }



}
