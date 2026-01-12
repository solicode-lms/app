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
     * Méthode helper pour créer une RealisationTache après vérification des règles :
     * 1. Le chapitre lié ne doit pas être déjà validé (DONE).
     * 2. La tâche ne doit pas déjà exister pour ce projet.
     * 
     * @param Tache $tache
     * @param RealisationProjet $realisationProjet
     * @param RealisationUaService $realisationUaService
     * @param int|null $etatInitialId
     * @param int|null $tacheAffectationId
     * @return void
     */
    public function createRealisationTacheIfEligible(
        Tache $tache,
        RealisationProjet $realisationProjet,
        RealisationUaService $realisationUaService,
        ?int $etatInitialId = null,
        ?int $tacheAffectationId = null
    ): void {
        // 1. Vérification : Chapitre déjà terminé ?
        if ($tache->chapitre) {
            $realisationUA = $realisationUaService->getOrCreateApprenant(
                $realisationProjet->apprenant_id,
                $tache->chapitre->unite_apprentissage_id
            );

            $chapitreExistant = RealisationChapitre::where('chapitre_id', $tache->chapitre->id)
                ->where('realisation_ua_id', $realisationUA->id)
                ->first();

            if ($chapitreExistant && $chapitreExistant->etatRealisationChapitre?->code === 'DONE') {
                return; // 🚫 Déjà validé, on ignore
            }
        }

        // 2. Vérification : Doublon existence tâche ?
        $existeRT = $realisationProjet->realisationTaches()->where('tache_id', $tache->id)->exists();
        if ($existeRT) {
            return; // 🚫 Existe déjà
        }



        // 3. Création
        $this->create([
            'realisation_projet_id' => $realisationProjet->id,
            'tache_id' => $tache->id,
            'etat_realisation_tache_id' => $etatInitialId,
            'tache_affectation_id' => $tacheAffectationId,
        ]);
    }

    /**
     * Règle métier exécutée avant la création d'une RealisationTache.
     * Si le champ `tache_affectation_id` n'est pas fourni :
     *  - on le recherche dans la table `tache_affectations`
     *  - sinon on le crée automatiquement à partir de la Tâche et de l'AffectationProjet
     * 
     * @param mixed $data
     * @return mixed
     */
    public function beforeCreateRules(&$data)
    {
        // 🧩 Si tache_affectation_id est vide → on le détermine ou le crée
        if (empty($data['tache_affectation_id']) && !empty($data['tache_id']) && !empty($data['realisation_projet_id'])) {

            $tache = \Modules\PkgCreationTache\Models\Tache::find($data['tache_id']);
            $realisationProjet = \Modules\PkgRealisationProjets\Models\RealisationProjet::find($data['realisation_projet_id']);

            if ($tache && $realisationProjet && $realisationProjet->affectation_projet_id) {
                $affectationProjetId = $realisationProjet->affectation_projet_id;

                // 🔍 Chercher si une TacheAffectation existe déjà
                $tacheAffectation = \Modules\PkgRealisationTache\Models\TacheAffectation::where('tache_id', $tache->id)
                    ->where('affectation_projet_id', $affectationProjetId)
                    ->first();

                // 🧱 Si elle n'existe pas, on la crée automatiquement
                if (!$tacheAffectation) {
                    $tacheAffectation = \Modules\PkgRealisationTache\Models\TacheAffectation::create([
                        'tache_id' => $tache->id,
                        'affectation_projet_id' => $affectationProjetId,
                        // Ajout de champs de sécurité pour compatibilité
                        'date_debut' => $realisationProjet->date_debut ?? now(),
                        'date_fin' => $realisationProjet->date_fin ?? now()->addWeek(),
                    ]);
                }

                // ✅ Injection de la valeur dans les données de création
                $data['tache_affectation_id'] = $tacheAffectation->id;
            }
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


}
