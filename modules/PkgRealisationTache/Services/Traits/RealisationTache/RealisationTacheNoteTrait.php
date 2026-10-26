<?php

namespace Modules\PkgRealisationTache\Services\Traits\RealisationTache;

use Modules\PkgRealisationTache\Models\RealisationTache;

/**
 * Trait dédié à la répartition et à l'arrondi mathématique des notes sur les prototypes et projets (N2/N3).
 */
trait RealisationTacheNoteTrait
{
    /**
     * Répartit la note globale sur les prototypes associés (N2).
     *
     * @param RealisationTache $tache
     * @return void
     */
    public function repartirNoteDansRealisationUaPrototypes(RealisationTache $tache): void
    {
        $this->repartirNoteDansElements($tache->realisationUaPrototypes, $tache->note ?? 0);
    }

    /**
     * Répartit la note globale sur les projets associés (N3).
     *
     * @param RealisationTache $tache
     * @return void
     */
    public function repartirNoteDansRealisationUaProjets(RealisationTache $tache): void
    {
        $this->repartirNoteDansElements($tache->realisationUaProjets, $tache->note ?? 0);
    }

    /**
     * Répartit la note de la tâche sur les éléments liés (prototypes ou projets),
     * en fonction du taux de remplissage (note / barème),
     * tout en respectant les barèmes et en arrondissant à 0.25.
     *
     * @param \Illuminate\Database\Eloquent\Collection $elements
     * @param float $noteTotale
     * @return void
     */
    public function repartirNoteDansElements(\Illuminate\Database\Eloquent\Collection $elements, float $noteTotale): void
    {
        if ($elements->isEmpty() || $noteTotale === null) {
            return;
        }

        $STEP_ROUNDING = 0.5;

        // Ne garder que les prototypes avec un barème > 0
        $elements = $elements->filter(fn($p) => $p->bareme > 0);
        if ($elements->isEmpty()) {
            return;
        }

        $roundToStep = fn($value) => round($value / $STEP_ROUNDING) * $STEP_ROUNDING;

        // Étape 1 : calcul du total des taux de remplissage
        $totalRemplissage = $elements->sum(function ($p) {
            $note = $p->note ?? 0;
            return $note / $p->bareme;
        });

        $useBareme = false;
        if ($totalRemplissage <= 0) {
            $totalRemplissage = $elements->sum(fn($p) => $p->bareme);
            $useBareme = true;
        }

        $repartitions = [];

        // 1️⃣ Répartition initiale avec arrondi à 0.25
        $totalAttribue = 0;
        foreach ($elements as $p) {
            $note = $p->note ?? 0;
            $remplissage = $note / $p->bareme;
            $ratio = $useBareme ? $p->bareme / $totalRemplissage : $remplissage / $totalRemplissage;
            $noteProposee = $roundToStep($noteTotale * $ratio);
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
        $ecart = round($noteTotale - $totalAttribue, 2);
        $step = 0.25;
        if (abs($ecart) >= 0.01) {
            $maxIterations = 1000;
            $i = 0;

            while (abs($ecart) >= 0.01 && $i < $maxIterations) {
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

                unset($entry);

                if (!$modification) {
                    break;
                }
                $i++;
            }

            // Si l'écart résiduel est exactement ±0.25
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

        // 3️⃣ Application finale
        foreach ($repartitions as $entry) {
            $entry['proto']->note = $entry['note_appliquee'];
            $entry['proto']->save();
        }
    }
}
