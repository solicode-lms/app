<?php

namespace Modules\PkgCreationTache\Services\Traits\Tache;

use Illuminate\Database\Eloquent\Builder;
use Modules\PkgCreationProjet\Models\EquipeProjet;

/**
 * Trait TacheGetterTrait
 * 
 * Gestion de la logique de récupération et filtres de requêtes.
 */
trait TacheGetterTrait
{
    /**
     * Récupérer les tâches associées aux projets d'un formateur donné.
     *
     * @param int $formateurId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTacheByFormateurId(int $formateurId)
    {
        return $this->model->whereHas('projet', function ($query) use ($formateurId) {
            $query->where('formateur_id', $formateurId);
        })->get();
    }

    /**
     * Récupérer les tâches associées aux projets d'un apprenant donné.
     *
     * @param int $apprenantId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTacheByApprenantId(int $apprenantId)
    {
        return $this->model->whereHas('realisationTaches', function ($query) use ($apprenantId) {
            $query->whereHas('realisationProjet', function ($q) use ($apprenantId) {
                $q->where('apprenant_id', $apprenantId);
            });
        })->get();
    }

    /**
     * Récupérer les tâches associées à une affectation de projet donnée.
     *
     * @param int $affectationProjetId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTacheByAffectationProjetId(int $affectationProjetId)
    {
        return $this->model->whereHas('projet', function ($query) use ($affectationProjetId) {
            $query->whereHas('affectationProjets', function ($q) use ($affectationProjetId) {
                $q->where('id', $affectationProjetId);
            });
        })->get();
    }

    /**
     * Récupère les données filtrées pour les listes dépendantes AJAX (targetDynamicDropdown).
     * Lors du filtrage par equipe_projet_id, inclut les tâches assignées à l'équipe
     * ainsi que les tâches communes du projet (equipe_projet_id IS NULL).
     *
     * @param string $filter
     * @param mixed $value
     * @return mixed
     */
    public function getData(string $filter, $value)
    {
        if ($filter === 'equipe_projet_id' && !empty($value)) {
            return $this->model::withScope(function () use ($value) {
                $query = $this->allQuery();
                $equipe = EquipeProjet::find($value);
                if ($equipe) {
                    $query->where('projet_id', $equipe->projet_id)
                          ->where(function ($q) use ($value) {
                              $q->where('equipe_projet_id', $value)
                                ->orWhereNull('equipe_projet_id');
                          });
                } else {
                    $query->where(function ($q) use ($value) {
                        $q->where('equipe_projet_id', $value)
                          ->orWhereNull('equipe_projet_id');
                    });
                }
                return $query->get();
            });
        }

        return parent::getData($filter, $value);
    }

    public function allQuery(array $params = [], $query = null): Builder
    {
        $query = parent::allQuery($params, $query);

        return $query;
    }
}

