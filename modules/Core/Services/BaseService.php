<?php

namespace Modules\Core\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Services\Contracts\ServiceInterface;

use Modules\Core\Services\Traits\{
    MessageTrait,
    PaginateTrait,
    QueryBuilderTrait,
    CrudTrait,
    RelationTrait,
    FilterTrait,
    StatsTrait
};


/**
 * Classe abstraite BaseService qui fournit une implémentation de base
 * pour les opérations courantes de manipulation des données.
 */
abstract class BaseService implements ServiceInterface
{

    use 
        MessageTrait,
        PaginateTrait, 
        QueryBuilderTrait, 
        CrudTrait, 
        RelationTrait, 
        FilterTrait, 
        StatsTrait;


    protected array $fieldsFilterable;
    protected $viewState;
    protected $sessionState;
    protected $model;
    protected $modelName;
    protected $paginationLimit = 20;

    protected $totalFilteredCount;

    /**
     * Méthode abstraite pour obtenir les champs recherchables.
     *
     * @return array
     */
    abstract public function getFieldsSearchable(): array;

    /**
     * Constructeur de la classe BaseService.
     *
     * @param Model $model Le modèle Eloquent associé au référentiel.
     */
    public function __construct(Model $model){
        $this->model = $model;
        $this->modelName = lcfirst(class_basename($model));
        // Scrop management
        $this->viewState = app(ViewStateService::class);
        $this->sessionState = app(SessionState::class);
    }

    public function getData(string $filter, $value)
    {
        $query = $this->model->newQuery(); // Créer une nouvelle requête

        // Construire le tableau de filtres pour la méthode `filter()`
        $filters = [$filter => $value];

        // Appliquer le filtre existant du service
        $this->filter($query, $this->model, $filters);

        return $query->get();
    }

    protected function reorderOrdreColumn(?int $ancienOrdre, int $nouvelOrdre, int $idEnCours = null): void
    {
        if ($ancienOrdre === null || $nouvelOrdre === $ancienOrdre) {
            return;
        }

        $query = $this->model->newQuery();

        if ($idEnCours !== null) {
            $query->where('id', '!=', $idEnCours);
        }

        // Cas où l'élément descend → les autres montent
        if ($nouvelOrdre > $ancienOrdre) {
            $query->whereBetween('ordre', [$ancienOrdre + 1, $nouvelOrdre])
                ->decrement('ordre');
        }
        // Cas où l'élément monte → les autres descendent
        else {
            $query->whereBetween('ordre', [$nouvelOrdre, $ancienOrdre - 1])
                ->increment('ordre');
        }
    }

}