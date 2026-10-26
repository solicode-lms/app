<?php

namespace Modules\PkgRealisationTache\Services\Traits\RealisationTache;

use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgApprentissage\Services\RealisationUaService;
use Modules\PkgApprentissage\Services\RealisationChapitreService;
use Modules\PkgApprentissage\Models\RealisationChapitre;
use Modules\PkgCreationProjet\Models\MobilisationUa;
use Modules\PkgCreationTache\Models\Tache;

trait RealisationTacheCrudTrait
{

    /**
     * Règle métier exécutée avant la création d'une RealisationTache.
     * 1. Détermine automatiquement `tache_affectation_id` si manquant.
     * 2. Ajuste `etat_realisation_tache_id` si le chapitre est déjà validé.
     */
    public function beforeCreateRules(array &$data): void
    {
        // Règle : Assigner la note du projet d'origine si la tâche a projet_origine_note_id
        if (!empty($data['tache_id']) && !empty($data['realisation_projet_id'])) {
            $tache = \Modules\PkgCreationTache\Models\Tache::find($data['tache_id']);
            if ($tache && !empty($tache->projet_origine_note_id)) {
                $realisationProjet = \Modules\PkgRealisationProjets\Models\RealisationProjet::find($data['realisation_projet_id']);
                if ($realisationProjet) {
                    $apprenantId = $realisationProjet->apprenant_id;
                    $projetOrigineNoteId = $tache->projet_origine_note_id;

                    $realisationProjetOrigine = \Modules\PkgRealisationProjets\Models\RealisationProjet::where('apprenant_id', $apprenantId)
                        ->whereHas('affectationProjet', function ($q) use ($projetOrigineNoteId) {
                            $q->where('projet_id', $projetOrigineNoteId);
                        })->first();

                    if ($realisationProjetOrigine) {
                        $data['note'] = $realisationProjetOrigine->calculerNoteAvecEchelle();
                    }
                }
            }
        }

        // 🛡️ Plafonnement de la note par rapport au barème de la tâche
        if (isset($data['note']) && !empty($data['tache_id'])) {
            $tache = \Modules\PkgCreationTache\Models\Tache::find($data['tache_id']);
            if ($tache && $tache->note !== null && $data['note'] > $tache->note) {
                $data['note'] = $tache->note;
            }
        }

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
        // on crée la tâche directement à l'état "APPROVED" (Validé) au lieu d'annuler.
        // Cela permet de garder une trace et de mettre à jour la progression.
        if ($this->shouldSkipCreationIfChapitreDone($data)) {
            $etatApproved = \Modules\PkgRealisationTache\Models\EtatRealisationTache::whereHas('workflowTache', function ($q) {
                $q->where('code', 'APPROVED');
            })->first();

            if ($etatApproved) {
                $data['etat_realisation_tache_id'] = $etatApproved->id;
                $data['date_fin'] = now();
                $data['date_debut'] = $data['date_debut'] ?? now();
                // On met une note par défaut si nécessaire (ex: note maximale ou note du chapitre) ??
                // Pour l'instant on laisse la note vide ou gérée par ailleurs.
            }
            // On n'annule PLUS la création
            // $data['__abort_creation'] = true; 
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
                    } else {
                        // Cas où le chapitre est déjà validé (DONE) : on informe l'utilisateur
                        $chapitreExistant->loadMissing('realisationTache.tache', 'realisationTache.realisationProjet.affectationProjet.projet');
                        $tacheTitre = $chapitreExistant->realisationTache?->tache?->titre ?? 'N/A';
                        $projetTitre = $chapitreExistant->realisationTache?->realisationProjet?->affectationProjet?->projet?->titre ?? 'N/A';

                        $realisationTache->update([
                            'remarques_formateur' => "Ce chapitre est déjà validé dans la tâche : [{$tacheTitre}] du projet : [{$projetTitre}]."
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

            // 🧩 Gestion consolidée des Compétences (N2/N3) via ActionsTrait
            $this->syncRealisationPrototypeEtProjetAvecMobilisations($realisationTache);

            // 🎯 Mise à jour du pourcentage de réalisation dans TacheAffectation
            if ($realisationTache->tache_affectation_id) {
                $tacheAffectationService = new \Modules\PkgRealisationTache\Services\TacheAffectationService();
                $tacheAffectation = $realisationTache->tacheAffectation ?? \Modules\PkgRealisationTache\Models\TacheAffectation::find($realisationTache->tache_affectation_id);
                if ($tacheAffectation) {
                    $tacheAffectationService->mettreAjourTacheProgression($tacheAffectation);
                }
            }

            // Mettre à jour la réalisation de projet, les UAs et les chapitres si l'état ou la note est fourni(e) pendant la création
            if ($realisationTache->note !== null || !empty($realisationTache->etat_realisation_tache_id)) {
                if ($realisationProjet) {
                    $realisationProjetService = app(\Modules\PkgRealisationProjets\Services\RealisationProjetService::class);
                    $realisationProjetService->mettreAJourEtatDepuisRealisationTaches($realisationProjet);
                    $realisationProjetService->mettreAJourProgressionDepuisEtatDesTaches($realisationProjet);
                    if ($realisationTache->note !== null) {
                        $realisationProjetService->calculerNoteEtBaremeDepuisTaches($realisationProjet);
                    }
                }

                $realisationTache->loadMissing(['realisationUaPrototypes', 'realisationUaProjets', 'realisationChapitres']);

                $uaIds = collect();
                foreach ($realisationTache->realisationUaPrototypes as $proto) {
                    if ($proto->realisation_ua_id) {
                        $uaIds->push($proto->realisation_ua_id);
                    }
                }
                foreach ($realisationTache->realisationUaProjets as $protoProjet) {
                    if ($protoProjet->realisation_ua_id) {
                        $uaIds->push($protoProjet->realisation_ua_id);
                    }
                }
                $uaIds = $uaIds->unique()->filter();

                if ($uaIds->isNotEmpty()) {
                    $realisationUaService = new RealisationUaService();
                    $uas = \Modules\PkgApprentissage\Models\RealisationUa::whereIn('id', $uaIds)->get();
                    foreach ($uas as $ua) {
                        $realisationUaService->calculerProgression($ua);
                    }
                }

                if (!$realisationTache->realisationChapitres->isEmpty()) {
                    $realisationChapitreService = new RealisationChapitreService();
                    $realisationChapitreService->calculerProgression($realisationTache);
                }

                if ($realisationTache->tacheAffectation) {
                    $tacheAffectationService = app(\Modules\PkgRealisationTache\Services\TacheAffectationService::class);
                    $tacheAffectationService->mettreAjourTacheProgression($realisationTache->tacheAffectation);
                    $tacheAffectationService->lancerLiveCodingSiEligible($realisationTache->tacheAffectation);
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

        // 🔄 Bug 2 : Sélection manuelle du candidat pour le Live Coding
        if (isset($data['is_live_coding']) && $data['is_live_coding'] == true && !$realisationTache->is_live_coding) {
            $tacheAffectation = $realisationTache->tacheAffectation;
            if ($tacheAffectation) {
                // Désélectionner les autres apprenants de la même tâche (sans les scopes globaux pour éviter l'erreur MySQL 1093)
                RealisationTache::withoutGlobalScopes()
                    ->where('tache_affectation_id', $tacheAffectation->id)
                    ->where('id', '!=', $realisationTache->id)
                    ->where('is_live_coding', true)
                    ->update(['is_live_coding' => false]);
                
                // Mettre à jour le cache sur TacheAffectation
                $tacheAffectation->update([
                    'apprenant_live_coding_cache' => [
                        'apprenant' => (string) $realisationTache->realisationProjet->apprenant,
                        'realisation_tache_id' => $realisationTache->id,
                        'date' => now()->toDateTimeString(),
                    ]
                ]);

                // S'assurer que le candidat passe en READY_FOR_LIVE_CODING
                if (!isset($data['etat_realisation_tache_id'])) {
                    $formateurId = $tacheAffectation?->affectationProjet?->projet?->formateur_id;
                    $etatLiveCoding = \Modules\PkgRealisationTache\Models\EtatRealisationTache::whereHas('workflowTache', fn($q) =>
                        $q->where('code', 'READY_FOR_LIVE_CODING')
                    )->where('formateur_id', $formateurId)->first();
                    
                    if ($etatLiveCoding) {
                        $data['etat_realisation_tache_id'] = $etatLiveCoding->id;
                    }
                }
            }
        }

        // 🛡️ Empêcher la modification de la note si la tâche est en relation avec un projet d'origine note
        if (array_key_exists('note', $data) && $realisationTache->tache && !empty($realisationTache->tache->projet_origine_note_id)) {
            if ($data['note'] != $realisationTache->note) {
                $projetOrigineNom = $realisationTache->tache->projetOrigineNote?->titre ?? 'N/A';
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'note' => "La note de cette tâche est liée au projet d'origine [{$projetOrigineNom}] et ne peut pas être modifiée directement."
                ]);
            }
        }

        // 🛡️ Plafonnement de la note par rapport au barème de la tâche
        if (isset($data['note'])) {
            $tache = $realisationTache->tache;
            if ($tache && $tache->note !== null && $data['note'] > $tache->note) {
                $data['note'] = $tache->note;
            }
        }


        $currentUser = \Illuminate\Support\Facades\Auth::user();
        $isFormateur = $currentUser ? (
            $currentUser->hasRole(\Modules\PkgAutorisation\Models\Role::FORMATEUR_ROLE) ||
            $currentUser->hasRole(\Modules\PkgAutorisation\Models\Role::ADMIN_ROLE) ||
            $currentUser->hasRole(\Modules\PkgAutorisation\Models\Role::ADMIN_FORMATEUR_ROLE) ||
            $currentUser->hasRole('root')
        ) : true;

        // ❌ Bloquer l'état si la tâche ou ses micro-compétences associées ont des livrables manquants
        // on ne bloque que pour les livravle de la tache, on va pas utiliser les livrable des micro compétences
        if (
            !$isFormateur &&
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
            // $realisationMicro = $realisationTache->realisationChapitres
            //     ->map(fn($rc) => $rc->realisationUa?->realisationMicroCompetence) // un seul UA par chapitre
            //     ->filter(); // enlève les null

            // $livrablesManquantsMicro = $realisationMicro
            //     ->filter(fn($rmc) => empty($rmc->lien_livrable))
            //     ->map(fn($rmc) => "Autoformation : " . ($rmc->microCompetence?->titre ?? "Sans titre"));


            // 3️⃣ Si livrables manquants → bloquer
            if (
                ($livrablesManquantsTache->isNotEmpty()) &&
                in_array($etatCode, $etatsInterdits)
            ) {
                $listeManquants = $livrablesManquantsTache
                    ->map(fn($titre) => "<li>" . e($titre) . "</li>")
                    ->join('');

                $message = "<p>Impossible de passer à l’état « {$etat->nom} », </br> les livrables suivants sont requis mais non déposés :</p><ul>{$listeManquants}</ul>";

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'etat_realisation_tache_id' => $message
                ]);
            }
        }


        // 🔄 Règle métier : Transition depuis l'état ABSENT et gestion des états restreints
        $etatActuel = $realisationTache->etatRealisationTache;
        $etatActuelCode = optional($etatActuel?->workflowTache)->code;
        $isTransitionDepuisAbsent = ($etatActuelCode === 'ABSENT');
        $isAutoRedirectionVersRemediation = false;

        if (!$isFormateur && !empty($data["etat_realisation_tache_id"])) {
            $etatDemande = \Modules\PkgRealisationTache\Models\EtatRealisationTache::find($data["etat_realisation_tache_id"]);
            $codeEtatDemande = optional($etatDemande?->workflowTache)->code;

            // 1. Si l'apprenant reprend sa tâche depuis ABSENT vers IN_PROGRESS ("En cours")
            if ($isTransitionDepuisAbsent && $codeEtatDemande === 'IN_PROGRESS') {
                $etaitEnRemediation = false;

                // A. Vérifier dans la table remediations s'il existe une remédiation active non résolue
                if (\Illuminate\Support\Facades\Schema::hasTable('remediations')) {
                    $etaitEnRemediation = \Illuminate\Support\Facades\DB::table('remediations')
                        ->where('realisation_tache_id', $realisationTache->id)
                        ->where('est_resolu', 0)
                        ->exists();
                }

                // B. Vérifier de secours dans l'historique des changements d'état le dernier état avant l'absence
                if (!$etaitEnRemediation) {
                    $dernierChangement = \Modules\PkgRealisationTache\Models\HistoriqueRealisationTache::where('realisation_tache_id', $realisationTache->id)
                        ->where('changement', 'like', '%État%')
                        ->where('changement', 'not like', '%Absent%')
                        ->latest('id')
                        ->first();

                    if ($dernierChangement) {
                        $chg = mb_strtolower($dernierChangement->changement);
                        $etaitEnRemediation = (str_contains($chg, 'remédiation') || str_contains($chg, 'remediation'));
                    }
                }

                // Si la tâche était en remédiation avant l'absence, rediriger automatiquement vers EN_REMEDIATION
                if ($etaitEnRemediation) {
                    $formateurId = $realisationTache->realisationProjet?->affectationProjet?->projet?->formateur_id;
                    $etatRemediation = \Modules\PkgRealisationTache\Models\EtatRealisationTache::whereHas('workflowTache', fn($q) =>
                        $q->where('code', 'EN_REMEDIATION')
                    )->where(function ($q) use ($formateurId) {
                        if ($formateurId) {
                            $q->where('formateur_id', $formateurId)->orWhereNull('formateur_id');
                        } else {
                            $q->whereNull('formateur_id');
                        }
                    })->orderByRaw('formateur_id IS NULL ASC')->first();

                    if ($etatRemediation) {
                        $data["etat_realisation_tache_id"] = $etatRemediation->id;
                        $isAutoRedirectionVersRemediation = true;
                    }
                }
            }

            // 2. Vérifications et interdictions strictes côté apprenant
            $etatFinalCible = \Modules\PkgRealisationTache\Models\EtatRealisationTache::find($data["etat_realisation_tache_id"]);
            $codeFinalCible = optional($etatFinalCible?->workflowTache)->code;

            // Interdiction : Un apprenant ne peut jamais marquer une tâche comme ABSENT
            if ($codeFinalCible === 'ABSENT') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'etat_realisation_tache_id' => "Seul un formateur peut marquer une tâche comme absente."
                ]);
            }

            // Interdiction : Un apprenant ne peut pas affecter manuellement l'état EN_REMEDIATION
            if ($codeFinalCible === 'EN_REMEDIATION' && !$isAutoRedirectionVersRemediation) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'etat_realisation_tache_id' => "Seul un formateur peut affecter cet état de tâche."
                ]);
            }

            // Vérifier les permissions formateur de l'état cible (pour les états avec is_editable_only_by_formateur = true)
            if ($etatFinalCible) {
                if ($etatFinalCible->is_editable_only_by_formateur && !$isFormateur && !$isAutoRedirectionVersRemediation) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'etat_realisation_tache_id' => "Seul un formateur peut affecter cet état de tâche."
                    ]);
                }
                // ✅ Vérifie le respect de la priorité selon le workflow
                $workflowCode = optional($etatFinalCible->workflowTache)->code;
                if ($this->workflowExigeRespectDesPriorites($workflowCode)) {
                    $this->verifierTachesMoinsPrioritairesTerminees($realisationTache, $workflowCode);
                }
            }

            // Vérification si l'état actuel existe et est modifiable uniquement par un formateur
            if ($realisationTache->etatRealisationTache) {
                $isSortieAutoriseeDepuisAbsent = $isTransitionDepuisAbsent && ($isAutoRedirectionVersRemediation || $codeFinalCible === 'IN_PROGRESS');
                if (
                    $realisationTache->etatRealisationTache->is_editable_only_by_formateur
                    && $realisationTache->etatRealisationTache->id != $data["etat_realisation_tache_id"]
                    && !$isFormateur
                    && !$isSortieAutoriseeDepuisAbsent
                ) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'etat_realisation_tache_id' => "Cet état de projet doit être modifié par le formateur."
                    ]);
                }
            }
        }

        if ($currentUser && $currentUser->hasRole(\Modules\PkgAutorisation\Models\Role::FORMATEUR_ROLE)) {
            // Si des évaluateurs existent, s'assurer que l'utilisateur y figure
            $user = $currentUser;
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

        // Sprint 5 : Indicateur 05 (T_file) - Horodatage automatique entrée file & démarrage Live Coding (Tâches N2 Prototype uniquement)
        $isPrototypeN2 = $realisationTache->isPrototype() || optional($realisationTache->tache?->phaseEvaluation)->code === 'N2';
        if ($isPrototypeN2 && isset($data['etat_realisation_tache_id']) && $data['etat_realisation_tache_id'] != $realisationTache->etat_realisation_tache_id) {
            $nouvelEtat = \Modules\PkgRealisationTache\Models\EtatRealisationTache::find($data['etat_realisation_tache_id']);
            $nouveauWfCode = optional($nouvelEtat?->workflowTache)->code;
            if ($nouveauWfCode === 'READY_FOR_LIVE_CODING') {
                $data['cache_date_entree_file'] = now();
            } elseif ($nouveauWfCode === 'IN_LIVE_CODING') {
                $data['cache_date_debut_live_coding'] = now();
                $dateEntree = $realisationTache->cache_date_entree_file ?? $realisationTache->updated_at ?? now();
                $data['cache_delai_attente_minutes'] = max(0, (int) \Carbon\Carbon::parse($dateEntree)->diffInMinutes(now()));
            }
        }
    }


    /**
     * affectation de dataDebut = now()
     * @param int $id
     */
    public function afterEditRules($realisationTache, $id)
    {
        if (is_null($realisationTache->dateDebut)) {
            $realisationTache->dateDebut = now(); // avec heure et minute
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

        // 🔄 Résolution ou activation automatique de la remédiation dans la table remediations
        if (\Illuminate\Support\Facades\Schema::hasTable('remediations')) {
            $nouvelEtat = \Modules\PkgRealisationTache\Models\EtatRealisationTache::with('workflowTache')->find($realisationTache->etat_realisation_tache_id);
            $wfCode = optional($nouvelEtat?->workflowTache)->code;
            if (in_array($wfCode, ['VALIDEE', 'APPROVED'])) {
                \Illuminate\Support\Facades\DB::table('remediations')
                    ->where('realisation_tache_id', $realisationTache->id)
                    ->where('est_resolu', 0)
                    ->update([
                        'est_resolu' => 1,
                        'updated_at' => now(),
                    ]);
            } elseif ($wfCode === 'EN_REMEDIATION') {
                $existsRem = \Illuminate\Support\Facades\DB::table('remediations')
                    ->where('realisation_tache_id', $realisationTache->id)
                    ->exists();
                if (!$existsRem) {
                    $formateurId = $realisationTache->realisationProjet?->affectationProjet?->projet?->formateur_id ?? 1;
                    \Illuminate\Support\Facades\DB::table('remediations')->insert([
                        'realisation_tache_id' => $realisationTache->id,
                        'formateur_id' => $formateurId,
                        'type_remediation' => 'INDIVIDUELLE',
                        'diagnostic' => "Consolidation didactique requise",
                        'est_resolu' => 0,
                        'reference' => 'REM_' . $realisationTache->id . '_' . date('Ymd') . '_' . uniqid(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    \Illuminate\Support\Facades\DB::table('remediations')
                        ->where('realisation_tache_id', $realisationTache->id)
                        ->where('est_resolu', 1)
                        ->update(['est_resolu' => 0, 'updated_at' => now()]);
                }
            }
        }

        // 🔄 Synchronisation automatique des remédiations post-Live Coding si l'état ou le Live Coding a changé
        if ($realisationTache->wasChanged('etat_realisation_tache_id') || $realisationTache->wasChanged('is_live_coding')) {
            $affectation = $realisationTache->realisationProjet?->affectationProjet;
            if ($affectation && class_exists(\Modules\PkgTableauBord\Services\TableauBordService::class)) {
                try {
                    app(\Modules\PkgTableauBord\Services\TableauBordService::class)->synchroniserApprenantsEnRemediation($affectation);
                } catch (\Throwable $e) {
                    // Éviter de bloquer l'enregistrement
                }
            }
        }

        // 🔄 Propagation automatique de l'absence : lorsqu'un apprenant met la tâche suivante en cours (IN_PROGRESS),
        // les apprenants absents sur les tâches précédentes sont automatiquement marqués ABSENT sur cette tâche.
        if ($realisationTache->wasChanged('etat_realisation_tache_id')) {
            $nouvelEtat = \Modules\PkgRealisationTache\Models\EtatRealisationTache::with('workflowTache')->find($realisationTache->etat_realisation_tache_id);
            $wfCode = optional($nouvelEtat?->workflowTache)->code;
            if ($wfCode === 'IN_PROGRESS') {
                $this->propagerEtatAbsentPourTacheSuivante($realisationTache);
            }
        }
    }

    /**
     * Propage l'état ABSENT sur la tâche actuelle pour les apprenants absents de la même affectation
     * lorsqu'un pair commence la tâche suivante (état IN_PROGRESS).
     *
     * @param RealisationTache $realisationTache
     * @return void
     */
    protected function propagerEtatAbsentPourTacheSuivante(RealisationTache $realisationTache): void
    {
        $tacheActuelle = $realisationTache->tache;
        $affectation = $realisationTache->realisationProjet?->affectationProjet;

        if (!$tacheActuelle || !$affectation) {
            return;
        }

        // 1. Déterminer la séquence ordonnée des tâches du projet
        $tachesDuProjet = \Modules\PkgCreationTache\Models\Tache::where('projet_id', $tacheActuelle->projet_id)
            ->orderBy('priorite')
            ->orderBy('ordre')
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        $currentIndex = array_search($tacheActuelle->id, $tachesDuProjet);
        if ($currentIndex === false || $currentIndex === 0) {
            // Aucune tâche précédente dans ce projet
            return;
        }

        $tachesPrecedentesIds = array_slice($tachesDuProjet, 0, $currentIndex);

        // 2. Trouver l'état ABSENT pour le formateur du projet (ou état système)
        $formateurId = $affectation->projet?->formateur_id;
        $etatAbsent = \Modules\PkgRealisationTache\Models\EtatRealisationTache::whereHas('workflowTache', fn($q) =>
            $q->where('code', 'ABSENT')
        )->where(function ($q) use ($formateurId) {
            if ($formateurId) {
                $q->where('formateur_id', $formateurId)->orWhereNull('formateur_id');
            } else {
                $q->whereNull('formateur_id');
            }
        })->orderByRaw('formateur_id IS NULL ASC')->first();

        if (!$etatAbsent) {
            return;
        }

        // 3. Identifier les autres apprenants de la même affectation
        $autresRealisationsProjet = \Modules\PkgRealisationProjets\Models\RealisationProjet::where('affectation_projet_id', $affectation->id)
            ->where('id', '!=', $realisationTache->realisation_projet_id)
            ->get();

        foreach ($autresRealisationsProjet as $rp) {
            // Récupérer les réalisations précédentes de cet apprenant ordonnées
            $rtsPrecedentes = RealisationTache::where('realisation_projet_id', $rp->id)
                ->whereIn('tache_id', $tachesPrecedentesIds)
                ->get()
                ->keyBy('tache_id');

            // Vérifier en partant de la tâche la plus proche vers la plus ancienne
            $estAbsentSurPrecedente = false;
            for ($i = count($tachesPrecedentesIds) - 1; $i >= 0; $i--) {
                $tId = $tachesPrecedentesIds[$i];
                $rtPrec = $rtsPrecedentes->get($tId);
                if ($rtPrec) {
                    $wfCodePrec = optional($rtPrec->etatRealisationTache?->workflowTache)->code;
                    if ($wfCodePrec === 'ABSENT') {
                        $estAbsentSurPrecedente = true;
                        break;
                    } elseif (in_array($wfCodePrec, ['APPROVED', 'VALIDEE', 'VALIDE_INTERMEDIAIRE', 'IN_PROGRESS', 'TO_APPROVE'])) {
                        // L'apprenant est actif ou a validé une tâche plus récente
                        break;
                    }
                }
            }

            if ($estAbsentSurPrecedente) {
                $rtActuelle = RealisationTache::where('realisation_projet_id', $rp->id)
                    ->where('tache_id', $tacheActuelle->id)
                    ->first();

                if ($rtActuelle) {
                    $codeActuel = optional($rtActuelle->etatRealisationTache?->workflowTache)->code;
                    // Ne modifier que si la tâche n'est pas déjà ABSENT et n'est pas finalisée
                    if ($codeActuel !== 'ABSENT' && !in_array($codeActuel, ['APPROVED', 'VALIDEE', 'VALIDE_INTERMEDIAIRE', 'NOT_VALIDATED'])) {
                        $rtActuelle->update([
                            'etat_realisation_tache_id' => $etatAbsent->id,
                        ]);

                        \Modules\PkgRealisationTache\Models\HistoriqueRealisationTache::create([
                            'realisation_tache_id' => $rtActuelle->id,
                            'dateModification' => now(),
                            'changement' => 'État : Absent (Propagation automatique absence apprenant)',
                            'user_id' => \Illuminate\Support\Facades\Auth::id() ?? $affectation->projet?->formateur?->user_id,
                            'isFeedback' => false,
                        ]);
                    }
                }
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

                        // TODO : Problème : il récupére même les tâche qui ne fait pas partie de projet courant
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
