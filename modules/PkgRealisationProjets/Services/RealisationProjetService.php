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
        // Étape 1 : Affecter l'état avec l'ordre minimal si aucun état n'est défini
        if (empty($realisationProjet->etats_realisation_projet_id)) {
            $etatDefaut = EtatsRealisationProjet::orderBy('ordre', 'asc')->first();
            if ($etatDefaut) {
                $realisationProjet->etats_realisation_projet_id = $etatDefaut->id;
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
                        'action' => 'edit',
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
        $formateur_id = Auth::user()->hasRole(Role::FORMATEUR_ROLE)
            ? Auth::user()->formateur?->id
            : null;

        $affectationProjet = $realisationProjet->affectationProjet;
        $taches = $affectationProjet->projet->taches;
        $mobilisationUas = $affectationProjet->projet->mobilisationUas ?? collect();

        // Récupération de l'état initial de réalisation de tâche
        $etatInitialRealisationTache = $formateur_id
            ? (new EtatRealisationTacheService())->getDefaultEtatByFormateurId($formateur_id)
            : null;

        // Déclaration des variables pour les services
        $realisationTacheService = new RealisationTacheService();
        $realisationUaService = new RealisationUaService();

        $realisationChapitreService = app(RealisationChapitreService::class);
        $realisationUaProjetService = app(RealisationUaProjetService::class);
        $realisationUaPrototypeService = app(RealisationUaPrototypeService::class);
        $realisationMicroCompetenceService = app(RealisationMicroCompetenceService::class);

        // pour chaque tâche du projet, on crée une RealisationTache
        // et on lie les chapitres et RealisationChapitre, RealisationUAprojet, RealisationUAPrototype
        foreach ($taches as $tache) {


            $tacheAffectation = $tache->tacheAffectations
            ->where('affectation_projet_id', $affectationProjet->id)
            ->first();

            // Création de la RealisationTache
            $realisationTache = $realisationTacheService->create([
                'realisation_projet_id' => $realisationProjet->id,
                'tache_id' => $tache->id,
                'etat_realisation_tache_id' => $etatInitialRealisationTache?->id,
                'tache_affectation_id' => $tacheAffectation?->id
            ]);

            // Liaison avec les chapitres
            if($tache->chapitre) {

                //  Récupération ou création de la RealisationUA
                $realisationUA = $realisationUaService->getOrCreateApprenant(
                    $realisationProjet->apprenant_id,
                    $tache->chapitre->unite_apprentissage_id
                );

                // Vérification de l'existence d'une RealisationChapitre
                $tache->chapitreExistant = RealisationChapitre::where('chapitre_id', $tache->chapitre->id)
                    ->where('realisation_ua_id', $realisationUA->id)
                    ->whereNull('realisation_tache_id')
                    ->first();

                if ($tache->chapitreExistant) {
                    $tache->chapitreExistant->update(['realisation_tache_id' => $realisationTache->id]);
                } else {
                    $realisationChapitreService->create([
                        'realisation_tache_id' => $realisationTache->id,
                        'chapitre_id'          => $tache->chapitre->id,
                        'realisation_ua_id'    => $realisationUA->id
                    ]);
                }
            }

            // Création RealisationUaPrototype
            if($tache->phaseEvaluation?->code == "N2"){
                // Création des UA projets et prototypes pour chaque mobilisation d'UA
                    foreach ($mobilisationUas as $mobilisation) {


                        $realisationUA = $realisationUaService->getOrCreateApprenant(
                            $realisationProjet->apprenant_id,
                            $mobilisation->unite_apprentissage_id
                        );

                        $realisationUaPrototypeService->create([
                            'realisation_tache_id' => $realisationTache->id,
                            'realisation_ua_id'    => $realisationUA->id,
                            'bareme'               => $mobilisation->bareme_evaluation_prototype ?? 0,
                        ]);
                    }
            }

            // Création RealisationUaProjet
            if($tache->phaseEvaluation?->code == "N3"){
                    // Création des UA projets et prototypes pour chaque mobilisation d'UA
                    foreach ($mobilisationUas as $mobilisation) {

                    $realisationUA = $realisationUaService->getOrCreateApprenant(
                            $realisationProjet->apprenant_id,
                            $mobilisation->unite_apprentissage_id
                        );

                        $realisationUaProjetService->create([
                            'realisation_tache_id' => $realisationTache->id,
                            'realisation_ua_id'    => $realisationUA->id,
                            'bareme'               => $mobilisation->bareme_evaluation_projet ?? 0,
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
        if (collect($codesTaches)->every(fn($code) => $code === 'DONE')) {
            $nouvelEtatCode = 'DONE';

        // ✅ TO_APPROVE : toutes les tâches sont TO_APPROVE ou DONE
        } elseif (collect($codesTaches)->every(fn($code) => in_array($code, ['TO_APPROVE', 'DONE']))) {
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
     * Calcule et met à jour progression_cache, note_cache et bareme_cache
     * pour un projet donné.
     */
    public function mettreAJourProgressionDepuisEtatDesTaches(RealisationProjet $realisationProjet): void
    {
        $realisationProjet->loadMissing('realisationTaches.etatRealisationTache.workflowTache');

        $taches = $realisationProjet->realisationTaches;
        $total = $taches->count();

        if ($total === 0) {
            $realisationProjet->progression_cache = 0;
            $realisationProjet->save();
            return;
        }

        // Compter les tâches dont l’état ≠ TODO
        $realisees = $taches->filter(function ($tache) {
            return optional($tache->etatRealisationTache?->workflowTache)->code !== 'TODO';
        })->count();

        $progression = ($realisees / $total) * 100;

        $realisationProjet->progression_cache = round($progression, 2);
        $realisationProjet->save();
    }


}
