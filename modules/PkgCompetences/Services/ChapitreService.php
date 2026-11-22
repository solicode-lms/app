<?php

namespace Modules\PkgCompetences\Services;

use Modules\Core\Services\ViewStateService;
use Modules\PkgApprentissage\Services\RealisationChapitreService;
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
        // L'ordre d'un chapitre est calculé et groupé par Unité d'Apprentissage (unite_apprentissage_id)
        $this->ordreGroupColumn = 'unite_apprentissage_id';
    }

    /**
     * Création d'un chapitre avec génération du code si vide.
     */
    public function create(array|object $data)
    {
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
     * Override de reorderOrdreColumn pour grouper par unite_apprentissage_id.
     */
    protected function reorderOrdreColumn(?int $ancienOrdre, int $nouvelOrdre, int $idEnCoursModification = null, $uniteApprentissageId = null): void
    {
        $this->normalizeOrdreIfNeeded($uniteApprentissageId);

        if ($ancienOrdre !== null && $nouvelOrdre === $ancienOrdre) {
            return;
        }

        $query = $this->model->newQuery();

        if ($uniteApprentissageId !== null) {
            $query->where('unite_apprentissage_id', $uniteApprentissageId);
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
     * Override de normalizeOrdreIfNeeded pour grouper par unite_apprentissage_id.
     */
    protected function normalizeOrdreIfNeeded($uniteApprentissageId = null): void
    {
        $query = $this->model->newQuery();

        if ($uniteApprentissageId !== null) {
            $query->where('unite_apprentissage_id', $uniteApprentissageId);
        }

        $elementsSansOrdre = $query->where(function ($q) {
                $q->whereNull('ordre')->orWhere('ordre', '');
            })
            ->orderBy('id')
            ->get();

        if ($elementsSansOrdre->isEmpty()) {
            return;
        }

        // Trouver l'ordre max dans cette UA
        $maxOrdreQuery = $this->model->newQuery();
        if ($uniteApprentissageId !== null) {
            $maxOrdreQuery->where('unite_apprentissage_id', $uniteApprentissageId);
        }
        $maxOrdre = $maxOrdreQuery->max('ordre') ?? 0;

        foreach ($elementsSansOrdre as $element) {
            $maxOrdre++;
            $element->ordre = $maxOrdre;
            $element->save();
        }
    }

    


    public function getData(string $filter, $value)
    {

        $query = $this->allQuery(); // Créer une nouvelle requête

        // Construire le tableau de filtres pour la méthode `filter()`
        $filters = [$filter => $value];

        // Appliquer le filtre existant du service
        $this->filter($query, $this->model, $filters);

        $viewState = app(ViewStateService::class);
        $classServiceName =  $viewState->getModelNameFromContextKey() . "Service";
        $classServiceName = "RealisationChapitreService";
        $objetService = $this->resolveClassByName($classServiceName);
     
        $dataIds = $objetService->getAvailableFilterValues('chapitre_id');
       
        $data = $this->getByIds($dataIds);

        $data =  $query->whereIn('id', $dataIds)->get();

        return $data;

         
    }


    public function getOrCreateImitationChapitre(int $uniteApprentissageId)
    {
        $ua = \Modules\PkgCompetences\Models\UniteApprentissage::find($uniteApprentissageId);
        $titre = $ua ? 'Tutoriel ' . $ua->code . ' : ' . $ua->nom : 'Tutoriel UA';
        $code = $ua ? 'TUTO-' . $ua->code : 'TUTO-UA-' . $uniteApprentissageId;

        $chapitre = $this->model
            ->where('code', $code)
            ->first();

        if (!$chapitre) {
            $chapitre = $this->create([
                'unite_apprentissage_id' => $uniteApprentissageId,
                'is_imitation_ua' => true,
                'nom' => $titre,
                'code' => $code,
                'description' => 'Chapitre d\'imitation de l\'unité d\'apprentissage.',
            ]);
        }

        return $chapitre;
    }
}
