<?php

namespace Modules\PkgRealisationTache\Services\Traits\RealisationTache;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationTache\Services\WorkflowTacheService;

trait RealisationTacheActionsTrait
{
    /**
     * Initialise une RealisationTache pour un apprenant et crée les évaluations liées si nécessaire.
     * Cette méthode centralise la logique de création appelée depuis TacheRelationsTrait et RealisationProjetCrudTrait.
     * 
     * @param \Modules\PkgCreationTache\Models\Tache $tache
     * @param \Modules\PkgRealisationProjets\Models\RealisationProjet $realisationProjet
     * @param \Modules\PkgRealisationTache\Models\EtatRealisationTache|null $etatInitial
     * @return RealisationTache|null
     */
    public function initialiserRealisationTache($tache, $realisationProjet, $etatInitial = null)
    {
        // 1. Vérification d'existence
        if ($this->existsForTacheAndProject($tache->id, $realisationProjet->id)) {
            return null;
        }

        // 2. Création de la RealisationTache
        $realisationTache = $this->create([
            'tache_id' => $tache->id,
            'realisation_projet_id' => $realisationProjet->id,
            'etat_realisation_tache_id' => $etatInitial?->id,
            'dateDebut' => $tache->dateDebut,
            'dateFin' => $tache->dateFin,
        ]);

        if (!$realisationTache) {
            return null;
        }

        // 3. Création des Évaluations liées (si évaluateurs assignés)
        $affectation = $realisationProjet->affectationProjet;
        if ($affectation && $affectation->evaluateurs && $affectation->evaluateurs->isNotEmpty()) {
            $evaluationTacheService = app(\Modules\PkgEvaluateurs\Services\EvaluationRealisationTacheService::class);
            foreach ($affectation->evaluateurs as $evaluateur) {
                $evaluationProjet = \Modules\PkgEvaluateurs\Models\EvaluationRealisationProjet::firstWhere([
                    'realisation_projet_id' => $realisationProjet->id,
                    'evaluateur_id' => $evaluateur->id,
                ]);

                if ($evaluationProjet) {
                    $evaluationTacheService->create([
                        'realisation_tache_id' => $realisationTache->id,
                        'evaluateur_id' => $evaluateur->id,
                        'evaluation_realisation_projet_id' => $evaluationProjet->id,
                    ]);
                }
            }
        }

        return $realisationTache;
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



    public function repartirNoteDansRealisationUaPrototypes(RealisationTache $tache): void
    {
        $this->repartirNoteDansElements($tache->realisationUaPrototypes, $tache->note ?? 0);
    }

    public function repartirNoteDansRealisationUaProjets(RealisationTache $tache): void
    {
        $this->repartirNoteDansElements($tache->realisationUaProjets, $tache->note ?? 0);
    }


    /**
     * Répartit la note de la tâche sur les éléments liés (prototypes ou projets),
     * en fonction du taux de remplissage (note / barème),
     * tout en respectant les barèmes et en arrondissant à 0.25.
     *
     * ✅ À la fin, la somme exacte des notes des prototypes sera égale à la note de la tâche.
     *
     * 🔢 Exemple :
     *  - P1 = 3 / 5  → taux = 0.6
     *  - P2 = 3 / 6  → taux = 0.5
     *  - total taux = 1.1
     *  - Ratio P1 = 0.6 / 1.1 ≈ 0.5455
     *  - Ratio P2 = 0.5 / 1.1 ≈ 0.4545
     *  - Pour une note globale de 5 :
     *      P1 ≈ 2.73 → arrondi à 2.75
     *      P2 ≈ 2.27 → arrondi à 2.25
     */
    public function repartirNoteDansElements(\Illuminate\Database\Eloquent\Collection $elements, float $noteTotale): void
    {


        if ($elements->isEmpty() || $noteTotale === null) {
            return;
        }

        // ✅ Définition de la constante d’arrondi
        $STEP_ROUNDING = 0.5;

        // ⚠️ Ne garder que les prototypes avec un barème > 0
        $elements = $elements->filter(fn($p) => $p->bareme > 0);
        if ($elements->isEmpty())
            return;

        // 🧮 Fonction pour arrondir à un multiple de 0.25
        $roundToStep = fn($value) => round($value / $STEP_ROUNDING) * $STEP_ROUNDING;

        // 🎯 Étape 1 : calcul du total des taux de remplissage (note actuelle / barème)
        $totalRemplissage = $elements->sum(function ($p) {
            $note = $p->note ?? 0;
            return $note / $p->bareme;
        });

        // Si aucun taux valide → on sort
        $useBareme = false;
        if ($totalRemplissage <= 0) {
            // Aucun remplissage → on répartit selon le barème
            $totalRemplissage = $elements->sum(fn($p) => $p->bareme);
            $useBareme = true;
        }

        $repartitions = [];

        // 1️⃣ Répartition initiale avec arrondi à 0.25
        $totalAttribue = 0;
        foreach ($elements as $p) {
            $note = $p->note ?? 0;
            $remplissage = $note / $p->bareme; // Exemple : 3 / 5 = 0.6
            $ratio = $useBareme ? $p->bareme / $totalRemplissage : $remplissage / $totalRemplissage; // Exemple : 0.6 / 1.1 ≈ 0.5455
            $noteProposee = $roundToStep($noteTotale * $ratio); // Ex: 5 * 0.5455 ≈ 2.75
            $noteAppliquee = min($noteProposee, $p->bareme);
            $noteAppliquee = $roundToStep($noteAppliquee);

            $repartitions[] = [
                'proto' => $p,
                'note_appliquee' => $noteAppliquee,
                'reste_possible' => max($p->bareme - $noteAppliquee, 0),
            ];

            $totalAttribue += $noteAppliquee;
        }

        // 2️⃣ Correction finale : forcer la somme exacte = note de la tâche
        $ecart = round($noteTotale - $totalAttribue, 2); // positif ou négatif
        $step = 0.25;
        if (abs($ecart) >= 0.01) {
            $maxIterations = 1000;
            $i = 0;

            while (abs($ecart) >= 0.01 && $i < $maxIterations) {
                // Trier les prototypes par reste possible (ajout) ou note actuelle (retrait)
                usort($repartitions, function ($a, $b) use ($ecart) {
                    return $ecart > 0
                        ? $b['reste_possible'] <=> $a['reste_possible']
                        : $b['note_appliquee'] <=> $a['note_appliquee'];
                });

                $modification = false;

                foreach ($repartitions as &$entry) {
                    $proto = $entry['proto'];
                    $note = $entry['note_appliquee'];

                    if ($ecart > 0 && $note + $step <= $proto->bareme) {
                        $entry['note_appliquee'] += $step;
                        $ecart = round($ecart - $step, 2);
                        $modification = true;
                        break;
                    }

                    if ($ecart < 0 && $note - $step >= 0) {
                        $entry['note_appliquee'] -= $step;
                        $ecart = round($ecart + $step, 2);
                        $modification = true;
                        break;
                    }
                }

                unset($entry); // Sécurité

                if (!$modification)
                    break;
                $i++;
            }

            // ✅ Si l'écart résiduel est exactement ±0.25 → appliquer une dernière correction
            if (abs($ecart) === 0.25) {
                foreach ($repartitions as &$entry) {
                    $proto = $entry['proto'];
                    $note = $entry['note_appliquee'];

                    if ($ecart > 0 && $note + 0.25 <= $proto->bareme) {
                        $entry['note_appliquee'] += 0.25;
                        break;
                    }

                    if ($ecart < 0 && $note - 0.25 >= 0) {
                        $entry['note_appliquee'] -= 0.25;
                        break;
                    }
                }
                unset($entry);
            }
        }

        // 3️⃣ Application finale (arrondi garanti à 0.25)
        foreach ($repartitions as $entry) {
            $entry['proto']->note = $entry['note_appliquee'];

            // TODO : il ne doit pas lancer l'observer Update : RealisationTache
            $entry['proto']->save();
        }
    }


    /**
     * Synchronise les objets de réalisation de compétences (RealisationUaPrototype/Projet) pour cette tâche.
     * Cette méthode crée ou nettoie les ponts nécessaires entre la réalisation de tâche (élève) et les UA mobilisées sur le projet.
     *
     * @param RealisationTache $realisationTache
     * @return void
     */
    public function syncRealisationPrototypeEtProjetAvecMobilisations(RealisationTache $realisationTache): void
    {
        // 1. Charger les relations nécessaires
        $realisationTache->loadMissing([
            'tache.phaseEvaluation',
            'realisationProjet.affectationProjet.projet.mobilisationUas',
            'realisationProjet.apprenant'
        ]);

        $tache = $realisationTache->tache;
        if (!$tache)
            return;

        // 2. Vérifier si N2 ou N3
        $code = $tache->phaseEvaluation?->code;
        if (!in_array($code, ['N2', 'N3'])) {
            return;
        }

        // 3. Récupérer les mobilisations actuelles du projet (les UA valides)
        $mobilisations = $realisationTache->realisationProjet->affectationProjet->projet->mobilisationUas ?? collect();
        $validUaIds = $mobilisations->pluck('unite_apprentissage_id')->toArray();

        // Initialisation à la demande des services
        $realisationUaService = app(\Modules\PkgApprentissage\Services\RealisationUaService::class);
        $realisationUaProjetService = app(\Modules\PkgApprentissage\Services\RealisationUaProjetService::class);
        $realisationUaPrototypeService = app(\Modules\PkgApprentissage\Services\RealisationUaPrototypeService::class);

        // A. NETTOYAGE : Supprimer les ponts vers des UA qui ne sont plus mobilisées
        if ($code === 'N2') {
            \Modules\PkgApprentissage\Models\RealisationUaPrototype::where('realisation_tache_id', $realisationTache->id)
                ->whereHas('realisationUa', function ($q) use ($validUaIds) {
                    $q->whereNotIn('unite_apprentissage_id', $validUaIds);
                })->delete();
        } elseif ($code === 'N3') {
            \Modules\PkgApprentissage\Models\RealisationUaProjet::where('realisation_tache_id', $realisationTache->id)
                ->whereHas('realisationUa', function ($q) use ($validUaIds) {
                    $q->whereNotIn('unite_apprentissage_id', $validUaIds);
                })->delete();
        }

        // B. AJOUT : Créer les ponts manquants
        $apprenantId = $realisationTache->realisationProjet->apprenant_id;

        foreach ($mobilisations as $mobilisation) {
            // Récupérer ou créer RealisationUa
            $realisationUA = $realisationUaService->getOrCreateApprenant(
                $apprenantId,
                $mobilisation->unite_apprentissage_id
            );

            if ($code === 'N2') {
                $exists = \Modules\PkgApprentissage\Models\RealisationUaPrototype::where('realisation_tache_id', $realisationTache->id)
                    ->where('realisation_ua_id', $realisationUA->id)
                    ->exists();

                if (!$exists) {
                    $realisationUaPrototypeService->create([
                        'realisation_tache_id' => $realisationTache->id,
                        'realisation_ua_id' => $realisationUA->id,
                        'bareme' => $mobilisation->bareme_evaluation_prototype ?? 0,
                    ]);
                }
            } elseif ($code === 'N3') {
                $exists = \Modules\PkgApprentissage\Models\RealisationUaProjet::where('realisation_tache_id', $realisationTache->id)
                    ->where('realisation_ua_id', $realisationUA->id)
                    ->exists();

                if (!$exists) {
                    $realisationUaProjetService->create([
                        'realisation_tache_id' => $realisationTache->id,
                        'realisation_ua_id' => $realisationUA->id,
                        'bareme' => $mobilisation->bareme_evaluation_projet ?? 0,
                    ]);
                }
            }
        }
    }
}