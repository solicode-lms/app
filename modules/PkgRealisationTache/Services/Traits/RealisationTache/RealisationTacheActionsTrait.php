<?php

namespace Modules\PkgRealisationTache\Services\Traits\RealisationTache;

use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationTache\Services\EtatRealisationTacheService;
use Modules\PkgApprentissage\Services\RealisationUaService;
use Modules\PkgApprentissage\Models\RealisationChapitre;
use Modules\PkgCreationTache\Models\Tache;
use Modules\PkgCreationProjet\Models\MobilisationUa;

trait RealisationTacheActionsTrait
{
    /**
     * Rappeler le processus de création des tâches depuis l'affectation.
     * Cette méthode centralise la logique de création initiale des tâches.
     *
     * @param RealisationProjet $realisationProjet
     * @return void
     */
    public function createFromRealisationProjet(RealisationProjet $realisationProjet): void
    {
        $formateur_id = $realisationProjet->affectationProjet->projet->formateur_id;
        $affectationProjet = $realisationProjet->affectationProjet;
        $tacheAffectations = $affectationProjet->tacheAffectations;

        $etatInitialRealisationTache = $formateur_id
            ? (new EtatRealisationTacheService())->getDefaultEtatByFormateurId($formateur_id)
            : null;

        $realisationUaService = new RealisationUaService();

        foreach ($tacheAffectations as $tacheAffectation) {
            $tache = $tacheAffectation->tache;

            // ⚠️ Si la tâche est liée à un chapitre terminé, on passe à la suivante
            if ($tache->chapitre) {
                // Créer ou récupérer l'UA associée
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
            // L'appel à create() déclenchera afterCreateRules -> processPostCreation()
            $this->create([
                'realisation_projet_id' => $realisationProjet->id,
                'tache_id' => $tache->id,
                'etat_realisation_tache_id' => $etatInitialRealisationTache?->id,
                'tache_affectation_id' => $tacheAffectation->id,
            ]);
        }
    }

    /**
     * Crée les RealisationTache pour les tâches de type tutoriel (N1) associées à une mobilisation UA.
     * Vérifie si le chapitre est déjà validé pour ne pas créer de doublon inutile.
     *
     * @param RealisationProjet $realisationProjet
     * @param MobilisationUa $mobilisation
     * @return void
     */
    public function createFormMobilisation(RealisationProjet $realisationProjet, MobilisationUa $mobilisation): void
    {
        // Récupérer les tâches N1 (Tutoriels) liées à cette UA pour ce projet
        $tachesN1 = Tache::where('projet_id', $mobilisation->projet_id)
            ->whereHas('chapitre', function ($q) use ($mobilisation) {
                $q->where('unite_apprentissage_id', $mobilisation->unite_apprentissage_id);
            })
            ->get();

        $realisationUaService = new RealisationUaService();

        // S'assurer que la RealisationUA existe (point d'ancrage)
        $realisationUA = $realisationUaService->getOrCreateApprenant(
            $realisationProjet->apprenant_id,
            $mobilisation->unite_apprentissage_id
        );

        foreach ($tachesN1 as $tache) {
            if ($tache->chapitre) {
                // Vérifier si le chapitre est déjà validé par l'apprenant
                $chapitreExistant = RealisationChapitre::where('chapitre_id', $tache->chapitre->id)
                    ->where('realisation_ua_id', $realisationUA->id)
                    ->first();

                if ($chapitreExistant && $chapitreExistant->etatRealisationChapitre?->code === 'DONE') {
                    continue; // Déjà validé, on ignore
                }

                // Créer la RT si elle n'existe pas déjà
                $existeRT = $realisationProjet->realisationTaches()->where('tache_id', $tache->id)->exists();
                if (!$existeRT) {
                    // On essaie de trouver une tacheAffectation existante
                    $tacheAffectation = $realisationProjet->affectationProjet->tacheAffectations()
                        ->where('tache_id', $tache->id)
                        ->first();

                    $this->create([
                        'realisation_projet_id' => $realisationProjet->id,
                        'tache_id' => $tache->id,
                        'tache_affectation_id' => $tacheAffectation?->id,
                    ]);
                }
            }
        }
    }

    /**
     * Liste des codes de workflows imposant une validation de priorité après la modification
     */
    protected function workflowExigeRespectDesPriorites(?string $workflowCode): bool
    {
        if (!$workflowCode) {
            return false;
        }

        $workflowsBloquants = [
            'IN_PROGRESS',
            'TO_APPROVE',
            'APPROVED',
        ];

        return in_array($workflowCode, $workflowsBloquants, true);
    }

    /**
     * Vérifie que toutes les tâches de priorité inférieure soient terminées
     */
    protected function verifierTachesMoinsPrioritairesTerminees(RealisationTache $realisationTache, $workflowCode): void
    {
        if (!$this->workflowExigeRespectDesPriorites($workflowCode)) {
            return;
        }

        $realisationTache->loadMissing('etatRealisationTache.workflowTache', 'tache');

        $projetId = $realisationTache->realisation_projet_id;
        $prioriteActuelle = $realisationTache->tache?->priorite ?? null;

        if ($prioriteActuelle === null) {
            return;
        }

        $etatsFinaux = ['APPROVED', 'TO_APPROVE', 'READY_FOR_LIVE_CODING', 'NOT_VALIDATED'];

        $tachesBloquantes = RealisationTache::where('realisation_projet_id', $projetId)
            ->whereHas('tache', function ($query) use ($prioriteActuelle) {
                $query->whereNotNull('priorite')->where('priorite', '<', $prioriteActuelle);
            })
            ->where(function ($query) use ($etatsFinaux) {
                $query->whereDoesntHave('etatRealisationTache')
                    ->orWhereHas('etatRealisationTache.workflowTache', function ($q) use ($etatsFinaux) {
                        $q->whereNotIn('code', $etatsFinaux);
                    });
            })
            ->with('tache')
            ->get();

        if ($tachesBloquantes->isNotEmpty()) {
            $nomsTaches = $tachesBloquantes->pluck('tache.titre')->filter()->map(fn($nom) => "<li>" . e($nom) . "</li>")->join('');

            throw ValidationException::withMessages([
                'etat_realisation_tache_id' => "<p>Impossible de passer à cet état : les tâches plus prioritaires suivantes ne sont pas encore terminées</p><ul>$nomsTaches</ul>"
            ]);
        }
    }



    // Helper pour normaliser une remarque
    public function normalizeRemarque(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        // Supprimer balises HTML et espaces
        $clean = trim(strip_tags($value));

        // Si vide après nettoyage, on retourne null
        return $clean === '' ? null : $value;
    }

    /**
     * Met à jour l’état de la tâche si une remarque formateur est ajoutée ou modifiée
     */
    public function mettreAJourEtatRevisionSiRemarqueModifiee(RealisationTache $record, array &$data): void
    {
        if (!Auth::user()?->hasRole(Role::FORMATEUR_ROLE)) {
            return;
        }

        if (!array_key_exists('remarques_formateur', $data)) {
            return;
        }

        // if ($record->remarques_formateur === $data['remarques_formateur']) {
        //     return;
        // }

        // Utilisation
        $current = $this->normalizeRemarque($record->remarques_formateur);
        $incoming = $this->normalizeRemarque($data['remarques_formateur'] ?? null);
        if ($current === $incoming) {
            return;
        }



        // 🔒 Ne pas modifier si le formateur a explicitement changé l'état
        if (array_key_exists('etat_realisation_tache_id', $data)) {
            $etatActuelId = (string) ($record->etat_realisation_tache_id ?? '');
            $nouvelEtatId = trim((string) ($data['etat_realisation_tache_id'] ?? ''));

            // Si le formateur a défini un état différent de l'actuel, on ne modifie pas
            if ($nouvelEtatId !== '' && $nouvelEtatId != $etatActuelId) {
                return;
            }
        }

        // Ne pas modifier si la tâche est déjà en révision
        if ($record->etatRealisationTache?->workflowTache->code === 'REVISION_NECESSAIRE') {
            return;
        }

        // Ne pas modifier si la tâche est déjà dans un état final
        if (in_array($record->etatRealisationTache?->workflowTache->code, ['APPROVED', 'NOT_VALIDATED'])) {
            return;
        }

        $wk = (new WorkflowTacheService())->getOrCreateWorkflowRevision();

        $etatRevision = EtatRealisationTache::firstOrCreate([
            'workflow_tache_id' => $wk->id,
            'formateur_id' => Auth::user()?->formateur->id,
        ], [
            'nom' => $wk->titre,
            'description' => $wk->description,
            'is_editable_only_by_formateur' => false,
            'sys_color_id' => $wk->sys_color_id,
        ]);

        $data['etat_realisation_tache_id'] = $etatRevision->id;
    }



}