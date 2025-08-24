<?php


namespace Modules\PkgCompetences\Services;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Modules\PkgCompetences\Services\Base\BaseUniteApprentissageService;

/**
 * Classe UniteApprentissageService pour gérer la persistance de l'entité UniteApprentissage.
 */
class UniteApprentissageService extends BaseUniteApprentissageService
{

        protected $dataSources = [
        "uaNonAlignee" => [
            "title" => "Unité d'apprentissage non alignée",
            "method" => "uniteApprentissageNonAligneeQuery"
        ],
    ];


    


    public function uniteApprentissageNonAligneeQuery(): Builder
    {
            return $this->model
                ->newQuery()
                ->whereNotExists(function ($query) {
                    $query->selectRaw(1)
                        ->from('alignement_uas')
                        ->whereColumn('alignement_uas.unite_apprentissage_id', 'unite_apprentissages.id');
                });
    }

    public function getUaNonAlignee()
    {

        $query = $this->uniteApprentissageNonAligneeQuery();

        // return $query->get();

        return $query->get()->map(function ($entity) {

            return [
                'nom' => $entity->nom,
                'code' => $entity->code
            ];
        })->toArray(); // <-- Conversion finale en tableau associatif
    }
   
}
