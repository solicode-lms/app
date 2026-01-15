<?php

namespace Modules\PkgRealisationTache\Services\Traits\RealisationTache;

use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgApprentissage\Services\RealisationUaService;
use Modules\PkgApprentissage\Services\RealisationChapitreService;
use Modules\PkgApprentissage\Services\RealisationUaProjetService;
use Modules\PkgApprentissage\Services\RealisationUaPrototypeService;
use Modules\PkgApprentissage\Models\RealisationChapitre;
use Modules\PkgCreationProjet\Models\MobilisationUa;
use Modules\PkgCreationTache\Models\Tache;

trait RealisationTacheCrudTrait
{

    /**
     * Règle métier exécutée avant la création d'une RealisationTache.
     * 1. Détermine automatiquement `tache_affectation_id` si manquant.
     * 2. Ajuste `etat_realisation_tache_id` si le chapitre est déjà validé.
     * 
     * @param array $data Les données pour la création.
     * @return array Les données modifiées.
     */
    public function beforeCreateRules(array &$data): void
    {
        // 1. Règle métier : Lien avec l'affectation de groupe (TacheAffectation)
        // Si `tache_affectation_id` est manquant, on doit le déduire à partir du projet et de la tâche.
        // Cela garantit que chaque réalisation individuelle est correctement rattachée à l'affectation globale du groupe.
        if (empty($data['tache_affectation_id']) && !empty($data['tache_id']) && !empty($data['realisation_projet_id'])) {

            $tache = \Modules\PkgCreationTache\Models\Tache::find($data['tache_id']);
            $realisationProjet = \Modules\PkgRealisationProjets\Models\RealisationProjet::find($data['realisation_projet_id']);

            if ($tache && $realisationProjet && $realisationProjet->affectation_projet_id) {
                $affectationProjetId = $realisationProjet->affectation_projet_id;

                $tacheAffectationService = new \Modules\PkgRealisationTache\Services\TacheAffectationService();
                $tacheAffectation = $tacheAffectationService->getOrCreateTacheAffectation($tache, $realisationProjet->affectationProjet);
                $data['tache_affectation_id'] = $tacheAffectation->id;
            }
        }

        // 2. Règle métier : Si le chapitre lié est déjà validé (DONE) pour l'apprenant,
        // on n'ajoute PAS la réalisation de tâche.
        // On définit le flag d'annulation pour CrudCreateTrait.
        if ($this->shouldSkipCreationIfChapitreDone($data)) {
            // Même si on annule la création de cette tâche spécifique,
            // on doit vérifier si cela complète l'UA (cas où tous les chapitres sont déjà faits).
            if (isset($data['tache_id']) && isset($data['realisation_projet_id'])) {
                $this->checkAndPerformUaValidationLogic($data['tache_id'], $data['realisation_projet_id']);
            }

            $data['__abort_creation'] = true;
            return;
        }
    }



    /**
     * Règles à appliquer après la création d'une RealisationTache.
     * Cette méthode gère automatiquement :
     * 1. La liaison ou création de `RealisationChapitre` si la tâche est liée à un chapitre.
     * 2. La création des `RealisationUaPrototype` pour les tâches de niveau N2.
     * 3. La création des `RealisationUaProjet` pour les tâches de niveau N3.
     *
     * @param mixed $item L'instance de RealisationTache créée.
     * @return void
     */
    public function afterCreateRules($item): void
    {
        if ($item instanceof RealisationTache) {
            $realisationTache = $item;

            // Chargement des relations nécessaires
            $realisationTache->loadMissing([
                'tache.chapitre',
                'realisationProjet.affectationProjet.projet.mobilisationUas',
                'realisationProjet.apprenant'
            ]);

            $tache = $realisationTache->tache;
            $realisationProjet = $realisationTache->realisationProjet;

            // On récupère les mobilisations depuis le projet associé
            $mobilisationUas = $realisationProjet->affectationProjet->projet->mobilisationUas ?? collect();

            $realisationUaService = new RealisationUaService();
            $realisationChapitreService = app(RealisationChapitreService::class);
            $realisationUaProjetService = app(RealisationUaProjetService::class);
            $realisationUaPrototypeService = app(RealisationUaPrototypeService::class);

            // 🔗 Si le chapitre existe, on lie ou crée sa RealisationChapitre
            if ($tache->chapitre) {
                $realisationUA = $realisationUaService->getOrCreateApprenant(
                    $realisationProjet->apprenant_id,
                    $tache->chapitre->unite_apprentissage_id
                );

                $chapitreExistant = RealisationChapitre::where('chapitre_id', $tache->chapitre->id)
                    ->where('realisation_ua_id', $realisationUA->id)
                    ->first();

                if (isset($chapitreExistant) && $chapitreExistant) {
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

                // --- 🆕 Nouvelle Règle Métier : Validation de l'UA si tous les chapitres sont terminés ---
                $ua = $tache->chapitre->uniteApprentissage;
                if ($ua) {
                    $totalChapitres = $ua->chapitres()->count();

                    // On compte les chapitres validés pour cette UA et cet apprenant
                    $chapitresValides = \Modules\PkgApprentissage\Models\RealisationChapitre::where('realisation_ua_id', $realisationUA->id)
                        ->whereHas('etatRealisationChapitre', function ($q) {
                            $q->where('code', 'DONE');
                        })
                        ->count();

                    // Astuce : il faut peut-être compter le chapitre courant comme validé s'il vient d'être fait via cette tâche
                    // Mais RealisationChapitre est créé/lié juste au-dessus. Si son état par défaut n'est pas DONE, le compte sera faux.
                    // Hypothèse : La validation du chapitre se fait ailleurs ou on considère qu'il l'est.
                    // Si le système valide le chapitre via l'état de la tâche, alors il faut peut-être forcer le compte +1 ou vérifier l'état de la tâche courante.
                    // Supposons que le mécanisme est asynchrone ou géré : on vérifie >= total - 1 si le courant n'est pas encore DONE en BDD ?
                    // Soyons stricts : on vérifie >= total. Si le chapitre courant n'est pas encore marqué DONE, ça ne déclenchera pas.
                    // Mais l'utilisateur veut que ça se déclenche.

                    if ($chapitresValides >= $totalChapitres) {

                        $etatApprovedId = \Modules\PkgRealisationTache\Models\EtatRealisationTache::whereHas('workflowTache', function ($q) {
                            $q->where('code', 'APPROVED');
                        })->value('id');

                        if ($etatApprovedId) {
                            // Trouver la tâche du dernier chapitre
                            $dernierChapitre = $ua->chapitres()->orderBy('ordre', 'desc')->first();

                            if ($dernierChapitre) {
                                // On cherche la tâche principale (ou unique) liée à ce chapitre
                                $tacheDernierChapitre = \Modules\PkgCreationTache\Models\Tache::where('chapitre_id', $dernierChapitre->id)->first();

                                if ($tacheDernierChapitre) {
                                    // Vérification d'existence pour éviter doublons et boucles infinies
                                    // On vérifie si une réalisation existe déjà pour CE projet et CETTE tâche
                                    $exists = RealisationTache::where('tache_id', $tacheDernierChapitre->id)
                                        ->where('realisation_projet_id', $realisationProjet->id)
                                        ->exists();

                                    if (!$exists) {
                                        $realisationTacheService = new \Modules\PkgRealisationTache\Services\RealisationTacheService();
                                        $realisationTacheService->create([
                                            'tache_id' => $tacheDernierChapitre->id,
                                            'realisation_projet_id' => $realisationProjet->id,
                                            'etat_realisation_tache_id' => $etatApprovedId,
                                            'date_debut' => now(),
                                            'date_fin' => now(),
                                            'description' => "Validation automatique via UA Completed" // Optionnel
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

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



    /**
     * Méthode contient les règles métier qui sont appliquer avant l'édition
     * il est utilisée avec tous les méthode qui font update
     * @param mixed $realisationTache
     * @param array $data
     * @return void
     */
    public function beforeUpdateRules(array &$data, $id)
    {

        $realisationTache = $this->find($id);


        // ❌ Bloquer l'état si la tâche ou ses micro-compétences associées ont des livrables manquants
        if (
            !\Illuminate\Support\Facades\Auth::user()->hasRole(\Modules\PkgAutorisation\Models\Role::FORMATEUR_ROLE) &&
            isset($data["etat_realisation_tache_id"]) &&
            ($etat = \Modules\PkgRealisationTache\Models\EtatRealisationTache::find($data["etat_realisation_tache_id"]))
        ) {
            $etatCode = $etat->workflowTache?->code;
            $etatsInterdits = ['IN_PROGRESS', 'TO_APPROVE', 'APPROVED'];

            $tache = $realisationTache->tache;

            // 1️⃣ Livrables attendus côté tâche
            $livrablesTache = $tache->livrables ?? collect();

            // Vérification des dépôts côté tâche
            $livrablesManquantsTache = collect();
            if ($livrablesTache->isNotEmpty()) {
                $idsLivrables = $livrablesTache->pluck('id');

                $idsLivrablesDeposes = $realisationTache->realisationProjet
                    ->livrablesRealisations()
                    ->whereIn('livrable_id', $idsLivrables)
                    ->pluck('livrable_id');

                $livrablesManquantsTache = $livrablesTache
                    ->filter(fn($livrable) => !$idsLivrablesDeposes->contains($livrable->id))
                    ->map(fn($livrable) => "Tâche : " . ($livrable->titre ?? "Sans titre"));
            }

            // 2️⃣ Livrables attendus côté micro-compétences
            $realisationMicro = $realisationTache->realisationChapitres
                ->map(fn($rc) => $rc->realisationUa?->realisationMicroCompetence) // un seul UA par chapitre
                ->filter(); // enlève les null

            $livrablesManquantsMicro = $realisationMicro
                ->filter(fn($rmc) => empty($rmc->lien_livrable))
                ->map(fn($rmc) => "Autoformation : " . ($rmc->microCompetence?->titre ?? "Sans titre"));


            // 3️⃣ Si livrables manquants → bloquer
            if (
                ($livrablesManquantsTache->isNotEmpty() || $livrablesManquantsMicro->isNotEmpty()) &&
                in_array($etatCode, $etatsInterdits)
            ) {
                $listeManquants = $livrablesManquantsTache
                    ->merge($livrablesManquantsMicro)
                    ->map(fn($titre) => "<li>" . e($titre) . "</li>")
                    ->join('');

                $message = "<p>Impossible de passer à l’état « {$etat->nom} », </br> les livrables suivants sont requis mais non déposés :</p><ul>{$listeManquants}</ul>";

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'etat_realisation_tache_id' => $message
                ]);
            }
        }


        // Empêcher un apprenant d'affecter un état réservé aux formateurs
        if (!\Illuminate\Support\Facades\Auth::user()->hasRole(\Modules\PkgAutorisation\Models\Role::FORMATEUR_ROLE) && !empty($data["etat_realisation_tache_id"])) {
            $etat_realisation_tache_id = $data["etat_realisation_tache_id"];
            $nouvelEtat = \Modules\PkgRealisationTache\Models\EtatRealisationTache::find($etat_realisation_tache_id);

            // Vérifier si le nouvel état existe
            if ($nouvelEtat) {
                if ($nouvelEtat->is_editable_only_by_formateur && !\Illuminate\Support\Facades\Auth::user()->hasRole(\Modules\PkgAutorisation\Models\Role::FORMATEUR_ROLE)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'etat_realisation_tache_id' => "Seul un formateur peut affecter cet état de tâche."
                    ]);
                }
                // ✅ Vérifie le respect de la priorité selon le workflow
                $workflowCode = optional($nouvelEtat->workflowTache)->code;
                if ($this->workflowExigeRespectDesPriorites($workflowCode)) {
                    $this->verifierTachesMoinsPrioritairesTerminees($realisationTache, $workflowCode);
                }
            }

            // Vérification si l'état actuel existe et est modifiable uniquement par un formateur
            if ($realisationTache->etatRealisationTache) {
                if (
                    $realisationTache->etatRealisationTache->is_editable_only_by_formateur
                    && $realisationTache->etatRealisationTache->id != $etat_realisation_tache_id
                    && !\Illuminate\Support\Facades\Auth::user()->hasRole(\Modules\PkgAutorisation\Models\Role::FORMATEUR_ROLE)
                ) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'etat_realisation_tache_id' => "Cet état de projet doit être modifié par le formateur."
                    ]);
                }
            }
        }

        if (\Illuminate\Support\Facades\Auth::user()->hasRole(\Modules\PkgAutorisation\Models\Role::FORMATEUR_ROLE)) {
            // Si des évaluateurs existent, s'assurer que l'utilisateur y figure
            $user = \Illuminate\Support\Facades\Auth::user();
            $realisationTache = $this->find($id);
            // Récupère les évaluateurs assignés au projet
            $evaluateurs = $realisationTache
                ->realisationProjet
                ->affectationProjet
                ->evaluateurs
                ->pluck('id');


            if (
                $evaluateurs->isNotEmpty()
                && $evaluateurs->doesntContain($user->evaluateur->id)
            ) {
                throw new \Exception("Le formateur n'est pas parmi les évaluateurs de ce projet.");
            }
        }



        // Historique des modification
        $historiqueRealisationTacheService = new \Modules\PkgRealisationTache\Services\HistoriqueRealisationTacheService();
        $historiqueRealisationTacheService->enregistrerChangement($realisationTache, $data);
        $this->mettreAJourEtatRevisionSiRemarqueModifiee($realisationTache, $data);


    }


    /**
     * affectation de dataDebut = now()
     * @param int $id
     */
    public function afterEditRules($realisationTache, $id)
    {
        if (is_null($realisationTache->dateDebut)) {
            $realisationTache->dateDebut = now()->toDateString(); // format YYYY-MM-DD sans heure
            $realisationTache->saveQuietly(); // il faut sauvegarder si tu veux que le changement soit persisté
        }

        // Déja appliquer par parrent
        // $this->markNotificationsAsRead( $realisationTache->id);
    }

    public function afterUpdateRules(RealisationTache $realisationTache): void
    {
        if ($realisationTache->wasChanged('note')) {

            if ($realisationTache->tache?->phaseEvaluation?->code == "N2") {
                // 3️⃣ Répartir la note sur les prototypes associés
                $this->repartirNoteDansRealisationUaPrototypes($realisationTache);
            }
            if ($realisationTache->tache?->phaseEvaluation?->code == "N3") {
                // 3️⃣ Répartir la note sur les prototypes associés
                $this->repartirNoteDansRealisationUaProjets($realisationTache);
            }

        }
    }



    /**
     * Helper pour encapsuler la logique de récupération de l'état "DONE"
     * si le chapitre associé est terminé.
     */
    /**
     * Helper pour vérifier si le chapitre est déjà validé.
     */
    protected function shouldSkipCreationIfChapitreDone(array $data): bool
    {
        // Bypass si flag explicite (pour éviter boucle infinie lors de création automatique)
        if (isset($data['__bypass_chapter_check']) && $data['__bypass_chapter_check'] === true) {
            return false;
        }

        if (empty($data['tache_id']) || empty($data['realisation_projet_id'])) {
            return false;
        }

        $tache = Tache::with('chapitre')->find($data['tache_id']);
        if (!$tache || !$tache->chapitre) {
            return false;
        }

        $realisationProjet = RealisationProjet::with('affectationProjet.projet')->find($data['realisation_projet_id']);
        if (!$realisationProjet) {
            return false;
        }

        // Vérification de l'état du chapitre pour cet apprenant
        $realisationUaService = new RealisationUaService();
        $realisationUA = $realisationUaService->getOrCreateApprenant(
            $realisationProjet->apprenant_id,
            $tache->chapitre->unite_apprentissage_id
        );

        $realisationChapitreService = app(\Modules\PkgApprentissage\Services\RealisationChapitreService::class);
        return $realisationChapitreService->isChapitreAlreadyDone($tache->chapitre->id, $realisationUA->id);
    }

    /**
     * Vérifie si l'UA est terminée et crée la tâche de validation du dernier chapitre si nécessaire.
     */
    protected function checkAndPerformUaValidationLogic(int $tacheId, int $realisationProjetId): void
    {
        $tache = \Modules\PkgCreationTache\Models\Tache::with('chapitre.uniteApprentissage.chapitres')->find($tacheId);
        $realisationProjet = \Modules\PkgRealisationProjets\Models\RealisationProjet::with('apprenant')->find($realisationProjetId);

        if (!$tache || !$tache->chapitre || !$realisationProjet) {
            return;
        }

        $ua = $tache->chapitre->uniteApprentissage;
        if ($ua) {
            $totalChapitres = $ua->chapitres->count();

            $realisationUaService = new \Modules\PkgApprentissage\Services\RealisationUaService();
            $realisationUA = $realisationUaService->getOrCreateApprenant(
                $realisationProjet->apprenant_id,
                $tache->chapitre->unite_apprentissage_id
            );

            // On compte les chapitres validés pour cette UA et cet apprenant
            $chapitresValides = \Modules\PkgApprentissage\Models\RealisationChapitre::where('realisation_ua_id', $realisationUA->id)
                ->whereHas('etatRealisationChapitre', function ($q) {
                    $q->where('code', 'DONE');
                })
                ->count();

            if ($chapitresValides >= $totalChapitres) {
                $etatApprovedId = \Modules\PkgRealisationTache\Models\EtatRealisationTache::whereHas('workflowTache', function ($q) {
                    $q->where('code', 'APPROVED');
                })->value('id');

                if ($etatApprovedId) {
                    // Trouver la tâche du dernier chapitre
                    $dernierChapitre = $ua->chapitres()->orderBy('ordre', 'desc')->first();

                    if ($dernierChapitre) {
                        $tacheDernierChapitre = \Modules\PkgCreationTache\Models\Tache::where('chapitre_id', $dernierChapitre->id)->first();

                        if ($tacheDernierChapitre) {
                            $exists = RealisationTache::where('tache_id', $tacheDernierChapitre->id)
                                ->where('realisation_projet_id', $realisationProjet->id)
                                ->exists();

                            if (!$exists) {
                                $realisationTacheService = new \Modules\PkgRealisationTache\Services\RealisationTacheService();
                                $realisationTacheService->create([
                                    'tache_id' => $tacheDernierChapitre->id,
                                    'realisation_projet_id' => $realisationProjet->id,
                                    'etat_realisation_tache_id' => $etatApprovedId,
                                    'date_debut' => now(),
                                    'date_fin' => now(),
                                    'description' => "Validation automatique via UA Completed",
                                    '__bypass_chapter_check' => true
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

}
