<?php

namespace Modules\PkgCreationTache\Services\Traits\Tache;

use Illuminate\Database\Eloquent\Builder;

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

    public function allQuery(array $params = [], $query = null): Builder
    {
        $query = parent::allQuery($params, $query);

        return $query;
    }
}
