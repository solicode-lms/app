<?php

namespace Modules\PkgCompetences\Services;

use Modules\PkgCompetences\Models\UniteApprentissage;
use Modules\PkgCompetences\Services\Base\BaseChapitreService;

/**
 * Classe ChapitreService pour gérer la persistance de l'entité Chapitre.
 */
class ChapitreService extends BaseChapitreService
{
    public function __construct()
    {
        parent::__construct();
        $this->ordreGroupColumn = 'micro_competence_id'; // Permet de calculer l'ordre par micro_compétence
    }

    /**
     * Création d'un chapitre avec génération du code si vide.
     */
    public function create(array|object $data)
    {
        // Injecter micro_competence_id via unite_apprentissage
        if (isset($data['unite_apprentissage_id'])) {
            $ua = UniteApprentissage::find($data['unite_apprentissage_id']);
            $data['micro_competence_id'] = $ua?->micro_competence_id;
        }

        $chapitre = parent::create($data);

        // Générer code si vide
        if (empty($chapitre->code)) {
            $chapitre->code = $this->generateChapitreCode($chapitre);
            $chapitre->save();
        }

        return $chapitre;
    }

    /**
     * Génère un code unique pour un chapitre.
     * Exemple : MC-2-CH-15
     */
    protected function generateChapitreCode($chapitre): string
    {
        $ua_code = $chapitre->uniteApprentissage?->code ?? 'X';
        return "{$ua_code}-{$chapitre->ordre}";
    }

    /**
     * Mise à jour d'un chapitre avec recalcul du code si nécessaire.
     */
    public function update($id, array $data)
    {
        $chapitre = parent::update($id, $data);

        if (empty($chapitre->code)) {
            $chapitre->code = $this->generateChapitreCode($chapitre);
            $chapitre->save();
        }

        return $chapitre;
    }

    /**
     * Override de reorderOrdreColumn pour grouper par micro_competence_id.
     */
    protected function reorderOrdreColumn(?int $ancienOrdre, int $nouvelOrdre, int $idEnCoursModification = null, $microCompetenceId = null): void
    {
        $this->normalizeOrdreIfNeeded($microCompetenceId);

        if ($ancienOrdre !== null && $nouvelOrdre === $ancienOrdre) {
            return;
        }

        $query = $this->model->newQuery();

        // Filtrer par micro_competence via relation UniteApprentissage
        if ($microCompetenceId !== null) {
            $query->whereHas('uniteApprentissage', function ($q) use ($microCompetenceId) {
                $q->where('micro_competence_id', $microCompetenceId);
            });
        }

        if ($idEnCoursModification !== null) {
            $query->where('id', '!=', $idEnCoursModification);
        }

        if ($ancienOrdre === null) {
            $query->where('ordre', '>=', $nouvelOrdre)
                ->orderBy('ordre', 'desc')
                ->get()
                ->each(function ($item) {
                    $item->ordre += 1;
                    $item->save();
                });
        } else {
            if ($nouvelOrdre > $ancienOrdre) {
                $query->whereBetween('ordre', [$ancienOrdre + 1, $nouvelOrdre])
                    ->orderBy('ordre')
                    ->get()
                    ->each(function ($item) {
                        $item->ordre -= 1;
                        $item->save();
                    });
            } else {
                $query->whereBetween('ordre', [$nouvelOrdre, $ancienOrdre - 1])
                    ->orderBy('ordre', 'desc')
                    ->get()
                    ->each(function ($item) {
                        $item->ordre += 1;
                        $item->save();
                    });
            }
        }
    }

    /**
     * Override de normalizeOrdreIfNeeded pour grouper par micro_competence_id.
     */
    protected function normalizeOrdreIfNeeded($microCompetenceId = null): void
    {
        $query = $this->model->newQuery();

        // Filtrer par micro_competence via relation UniteApprentissage
        if ($microCompetenceId !== null) {
            $query->whereHas('uniteApprentissage', function ($q) use ($microCompetenceId) {
                $q->where('micro_competence_id', $microCompetenceId);
            });
        }

        $elementsSansOrdre = $query->where(function ($q) {
                $q->whereNull('ordre')->orWhere('ordre', '');
            })
            ->orderBy('id')
            ->get();

        if ($elementsSansOrdre->isEmpty()) {
            return;
        }

        // Trouver l'ordre max dans ce groupe
        $maxOrdreQuery = $this->model->newQuery();
        if ($microCompetenceId !== null) {
            $maxOrdreQuery->whereHas('uniteApprentissage', function ($q) use ($microCompetenceId) {
                $q->where('micro_competence_id', $microCompetenceId);
            });
        }
        $maxOrdre = $maxOrdreQuery->max('ordre') ?? 0;

        foreach ($elementsSansOrdre as $element) {
            $maxOrdre++;
            $element->ordre = $maxOrdre;
            $element->save();
        }
    }

    public function dataCalcul($chapitre)
    {
        // En Cas d'édit
        if (isset($chapitre->id)) {
            //
        }

        return $chapitre;
    }
}
