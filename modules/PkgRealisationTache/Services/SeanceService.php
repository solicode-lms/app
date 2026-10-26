<?php

namespace Modules\PkgRealisationTache\Services;

use Carbon\Carbon;

/**
 * Service gérant la configuration et le calcul des séances temporelles d'établissement.
 * Permet de déterminer la séance active, la plage horaire d'une séance ou de la journée.
 */
class SeanceService
{
    /**
     * Configuration par défaut des séances pour Solicode.
     * Chaque séance comporte un identifiant, un code, un nom lisible, une heure de début et de fin.
     *
     * @var array
     */
    protected array $seances = [
        [
            'id' => 'S1',
            'code' => 'SEANCE_1',
            'nom' => 'Séance 1 (09:00 - 11:30)',
            'debut' => '09:00',
            'fin' => '11:30',
        ],
        [
            'id' => 'S2',
            'code' => 'SEANCE_2',
            'nom' => 'Séance 2 (11:30 - 14:00)',
            'debut' => '11:30',
            'fin' => '14:00',
        ],
        [
            'id' => 'S3',
            'code' => 'SEANCE_3',
            'nom' => 'Séance 3 (14:30 - 17:00)',
            'debut' => '14:30',
            'fin' => '17:00',
        ],
    ];

    public function __construct(?array $customSeances = null)
    {
        if ($customSeances !== null) {
            $this->seances = $customSeances;
        } elseif (function_exists('config') && config()->has('etablissement.seances')) {
            $this->seances = config('etablissement.seances');
        }
    }

    /**
     * Récupère la liste des séances configurées.
     *
     * @return array
     */
    public function getSeances(): array
    {
        return $this->seances;
    }

    /**
     * Surcharge dynamique de la liste des séances.
     *
     * @param array $seances
     * @return self
     */
    public function setSeances(array $seances): self
    {
        $this->seances = $seances;
        return $this;
    }

    /**
     * Retourne la séance englobant le timestamp donné ($now).
     * Si l'heure courante est en dehors de toute séance, retourne null.
     *
     * @param Carbon|null $now
     * @return array|null
     */
    public function getCurrentSeance(?Carbon $now = null): ?array
    {
        $now = $now ? $now->copy() : Carbon::now();
        $timeStr = $now->format('H:i');

        foreach ($this->seances as $seance) {
            if ($timeStr >= $seance['debut'] && $timeStr <= $seance['fin']) {
                return $seance;
            }
        }

        return null;
    }

    /**
     * Retourne la séance courante ou la dernière séance écoulée de la journée.
     * Utile lorsque le formateur consulte le système pendant une pause inter-séances.
     *
     * @param Carbon|null $now
     * @return array
     */
    public function getLastOrCurrentSeance(?Carbon $now = null): array
    {
        $now = $now ? $now->copy() : Carbon::now();
        $current = $this->getCurrentSeance($now);
        if ($current !== null) {
            return $current;
        }

        $timeStr = $now->format('H:i');
        $lastPassed = null;

        foreach ($this->seances as $seance) {
            if ($timeStr > $seance['fin']) {
                $lastPassed = $seance;
            }
        }

        // Si avant 09:00, retourner la première séance
        return $lastPassed ?? $this->seances[0];
    }

    /**
     * Retourne la plage horaire [Carbon $start, Carbon $end] pour une séance donnée et une date donnée.
     *
     * @param array|string $seance
     * @param Carbon|null $date
     * @return array{0: Carbon, 1: Carbon}
     */
    public function getSeanceTimeRange(array|string $seance, ?Carbon $date = null): array
    {
        $date = $date ? $date->copy() : Carbon::now();
        $dateStr = $date->format('Y-m-d');

        if (is_string($seance)) {
            $found = null;
            foreach ($this->seances as $s) {
                if ($s['id'] === $seance || $s['code'] === $seance) {
                    $found = $s;
                    break;
                }
            }
            $seance = $found ?? $this->getLastOrCurrentSeance($date);
        }

        $start = Carbon::parse("{$dateStr} {$seance['debut']}:00");
        $end = Carbon::parse("{$dateStr} {$seance['fin']}:00");

        return [$start, $end];
    }

    /**
     * Retourne la plage de la journée [Carbon $start, Carbon $end].
     * De 09:00 à l'instant courant (ou fin de la journée).
     *
     * @param Carbon|null $date
     * @return array{0: Carbon, 1: Carbon}
     */
    public function getTodayTimeRange(?Carbon $date = null): array
    {
        $date = $date ? $date->copy() : Carbon::now();
        $dateStr = $date->format('Y-m-d');

        $firstSeance = $this->seances[0];
        $start = Carbon::parse("{$dateStr} {$firstSeance['debut']}:00");
        $end = $date->copy();

        if ($end->lt($start)) {
            $end = $start->copy();
        }

        return [$start, $end];
    }

    /**
     * Formate une durée en minutes en texte lisible (ex: "1h 45min", "25min").
     *
     * @param int $minutes
     * @return string
     */
    public function formatDuree(int $minutes): string
    {
        if ($minutes < 0) {
            $minutes = 0;
        }

        $hours = intdiv($minutes, 60);
        $remMinutes = $minutes % 60;

        if ($hours > 0 && $remMinutes > 0) {
            return "{$hours}h {$remMinutes}min";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$remMinutes}min";
        }
    }
}
