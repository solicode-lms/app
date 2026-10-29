<?php

namespace Modules\PkgRealisationTache\Services\Traits\RealisationTache;

use Modules\PkgRealisationTache\Models\RealisationTache;

/**
 * Trait dédié aux règles et hooks du cycle de vie de mise à jour (beforeUpdateRules, afterEditRules, afterUpdateRules)
 * pour RealisationTache.
 */
trait RealisationTacheCrudTrait
{
    /**
     * Méthode contenant les règles métier appliquées avant l'édition/mise à jour.
     *
     * @param array $data
     * @param int|string $id
     * @return void
     */
    public function beforeUpdateRules(array &$data, $id)
    {
        $realisationTache = $this->find($id);

        // 🔄 Sélection manuelle du candidat pour le Live Coding
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

        // ❌ Bloquer l'état si la tâche a des livrables manquants
        if (
            !$isFormateur &&
            isset($data["etat_realisation_tache_id"]) &&
            ($etat = \Modules\PkgRealisationTache\Models\EtatRealisationTache::find($data["etat_realisation_tache_id"]))
        ) {
            $etatCode = $etat->workflowTache?->code;
            $etatsInterdits = ['IN_PROGRESS', 'TO_APPROVE', 'APPROVED'];
            $tache = $realisationTache->tache;
            $livrablesTache = $tache->livrables ?? collect();
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

            if ($livrablesManquantsTache->isNotEmpty() && in_array($etatCode, $etatsInterdits)) {
                $listeManquants = $livrablesManquantsTache
                    ->map(fn($titre) => "<li>" . e($titre) . "</li>")
                    ->join('');

                $message = "<p>Impossible de passer à l’état « {$etat->nom} », </br> les livrables suivants sont requis mais non déposés :</p><ul>{$listeManquants}</ul>";

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'etat_realisation_tache_id' => $message
                ]);
            }
        }

        // 🔄 Règle métier : Transition depuis l'état ABSENT et gestion des remédiations (délégué au trait)
        $isAutoRedirectionVersRemediation = $this->gererReglesAbsenceAvantUpdate($realisationTache, $data, $isFormateur);

        if (!$isFormateur && !empty($data["etat_realisation_tache_id"])) {
            $etatFinalCible = \Modules\PkgRealisationTache\Models\EtatRealisationTache::find($data["etat_realisation_tache_id"]);
            $codeFinalCible = optional($etatFinalCible?->workflowTache)->code;

            // Interdiction : Un apprenant ne peut pas affecter manuellement l'état EN_REMEDIATION
            if ($codeFinalCible === 'EN_REMEDIATION' && !$isAutoRedirectionVersRemediation) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'etat_realisation_tache_id' => "Seul un formateur peut affecter cet état de tâche."
                ]);
            }

            // Vérifier les permissions formateur de l'état cible
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
                $etatActuelCode = optional($realisationTache->etatRealisationTache->workflowTache)->code;
                $isTransitionDepuisAbsent = ($etatActuelCode === 'ABSENT');
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
            $user = $currentUser;
            $evaluateurs = $realisationTache
                ->realisationProjet
                ->affectationProjet
                ->evaluateurs
                ->pluck('id');

            if ($evaluateurs->isNotEmpty() && $evaluateurs->doesntContain($user->evaluateur?->id)) {
                throw new \Exception("Le formateur n'est pas parmi les évaluateurs de ce projet.");
            }
        }

        // Historique des modifications
        $historiqueRealisationTacheService = new \Modules\PkgRealisationTache\Services\HistoriqueRealisationTacheService();
        $historiqueRealisationTacheService->enregistrerChangement($realisationTache, $data);
        $this->mettreAJourEtatRevisionSiRemarqueModifiee($realisationTache, $data);

        // Sprint 5 : Horodatage automatique entrée file & démarrage Live Coding (Tâches N2 Prototype)
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
     * Affectation de dateDebut = now() lors de la première édition.
     *
     * @param mixed $realisationTache
     * @param int|string $id
     */
    public function afterEditRules($realisationTache, $id)
    {
        if (is_null($realisationTache->dateDebut)) {
            $realisationTache->dateDebut = now();
            $realisationTache->saveQuietly();
        }
    }

    /**
     * Règles appliquées après la mise à jour d'une RealisationTache.
     *
     * @param RealisationTache $realisationTache
     * @return void
     */
    public function afterUpdateRules(RealisationTache $realisationTache): void
    {
        if ($realisationTache->wasChanged('note')) {
            if ($realisationTache->tache?->phaseEvaluation?->code == "N2") {
                $this->repartirNoteDansRealisationUaPrototypes($realisationTache);
            }
            if ($realisationTache->tache?->phaseEvaluation?->code == "N3") {
                $this->repartirNoteDansRealisationUaProjets($realisationTache);
            }
        }

        // 🔄 Synchronisation automatique de la remédiation dans la table remediations (délégué au trait)
        $this->synchroniserRemediationApresUpdate($realisationTache);

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

        // 🔄 Propagation automatique de l'absence
        if ($realisationTache->wasChanged('etat_realisation_tache_id')) {
            $nouvelEtat = \Modules\PkgRealisationTache\Models\EtatRealisationTache::with('workflowTache')->find($realisationTache->etat_realisation_tache_id);
            $wfCode = optional($nouvelEtat?->workflowTache)->code;
            if ($wfCode === 'IN_PROGRESS') {
                $this->propagerEtatAbsentPourTacheSuivante($realisationTache);
            }
        }
    }
}
