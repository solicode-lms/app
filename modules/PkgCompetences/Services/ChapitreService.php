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


        /**
         * Résout dynamiquement le nom de la classe à partir de son nom court (ex: "Apprenant"),
         * en cherchant dans les namespaces des modules déclarés dans SoliLMS.
         *
         * @param string $className Nom court de la classe (ex: "Apprenant")
         * @return object|null Instance de la classe si trouvée, sinon null
         */
        function resolveClassByName(string $className): ?object
        {
            $modulePaths = [
                'PkgApprenants',
                'PkgFormation',
                'PkgCompetences',
                'PkgCreationProjet',
                'PkgRealisationProjets',
                'PkgCreationTache',
                'PkgRealisationTache',
                'PkgApprentissage',
                'PkgEvaluateurs',
                'PkgNotification',
                'PkgAutorisation',
                'PkgWidgets',
                'PkgSessions',
                'PkgGapp',
                'Core'
            ];

            foreach ($modulePaths as $module) {
                $fqcn = "Modules\\$module\\Services\\$className";
                if (class_exists($fqcn)) {
                    return new $fqcn();
                }

                // En fallback, certains modules utilisent Entities à la place de Models
                $fqcnEntity = "Modules\\$module\\Models\\$className";
                if (class_exists($fqcnEntity)) {
                    return new $fqcnEntity();
                }
            }

            return null;
        }
    

}
