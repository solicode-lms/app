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


}