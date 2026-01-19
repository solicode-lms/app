<?php


namespace Modules\PkgCompetences\Services;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\PkgCompetences\Services\Base\BaseUniteApprentissageService;

/**
 * Classe UniteApprentissageService pour gérer la persistance de l'entité UniteApprentissage.
 */
class UniteApprentissageService extends BaseUniteApprentissageService
{
    /**
     * Surcharge de la méthode de tri par défaut.
     * Trie par Compétence -> Micro-Compétence -> Unité d'Apprentissage.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function defaultSort($query)
    {
        return $query
            ->select('unite_apprentissages.*')
            ->join('micro_competences', 'unite_apprentissages.micro_competence_id', '=', 'micro_competences.id')
            ->join('competences', 'micro_competences.competence_id', '=', 'competences.id')

            ->orderBy('unite_apprentissages.code', 'asc');
    }

    protected $dataSources = [
        "uaNonAlignee" => [
            "title" => "Unité d'apprentissage non alignée",
            "method" => "uniteApprentissageNonAligneeQuery"
        ],
    ];





    public function uniteApprentissageNonAligneeQuery(): Builder
    {
        $query = $this->model
            ->newQuery()
            ->whereNotExists(function ($sub) {
                $sub->selectRaw(1)
                    ->from('alignement_uas')
                    ->whereColumn('alignement_uas.unite_apprentissage_id', 'unite_apprentissages.id');
            });

        if (Auth::user()->hasRole('formateur')) {

            $formateurId = $this->sessionState->get('formateur_id');

            $query->whereExists(function ($sub) use ($formateurId) {
                $sub->selectRaw(1)
                    ->from('formateur_groupe')
                    ->join('groupes', 'groupes.id', '=', 'formateur_groupe.groupe_id')
                    ->join('filieres', 'filieres.id', '=', 'groupes.filiere_id')
                    ->join('modules', 'modules.filiere_id', '=', 'filieres.id')
                    ->join('competences', 'competences.module_id', '=', 'modules.id')
                    ->join('micro_competences', 'micro_competences.competence_id', '=', 'competences.id')
                    ->where('formateur_groupe.formateur_id', $formateurId);
                // ->whereColumn('micro_competences.id', 'unite_apprentissages.micro_competence_id');
            });
        }

        return $query;
    }

    public function getUaNonAlignee()
    {

        $query = $this->uniteApprentissageNonAligneeQuery();

        // return $query->get();

        return $query->get()->map(function ($entity) {

            return [
                'nom' => $entity->nom,
                'code' => $entity->code,
                'micro_competence' => $entity->microCompetence->titre
            ];
        })->toArray(); // <-- Conversion finale en tableau associatif
    }

}
