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

    protected  $fieldsSearchable;

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
     * Méthode pour obtenir les champs sortable.
     * Les champs dynamique sont sortable mais ne sont pas searchable
     *
     * @return array
     */
    public function getFieldsSortable(): array
    {
        $dynamicAttributes = array_keys($this->model->getDynamicAttributes());
        
        // On fusionne les champs "searchable" (en base) et les attributs dynamiques (calculés)
        return array_merge($this->fieldsSearchable, $dynamicAttributes);
    }
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
        $query = $this->allQuery(); // Créer une nouvelle requête

        // Construire le tableau de filtres pour la méthode `filter()`
        $filters = [$filter => $value];

        // Appliquer le filtre existant du service
        $this->filter($query, $this->model, $filters);

        return $query->get();
    }

    protected function reorderOrdreColumn(?int $ancienOrdre, int $nouvelOrdre, int $idEnCours = null): void
    {
        // Si ancien et nouvel ordre sont égaux, pas de traitement
        if ($ancienOrdre !== null && $nouvelOrdre === $ancienOrdre) {
            return;
        }
    
        $query = $this->model->newQuery();
    
        if ($idEnCours !== null) {
            $query->where('id', '!=', $idEnCours);
        }
    
        if ($ancienOrdre === null) {
            // ✅ Création dans une position spécifique → décaler tout vers le bas
            $query->where('ordre', '>=', $nouvelOrdre)
                  ->orderBy('ordre', 'desc') // ⚠️ Important pour éviter écrasement
                  ->get()
                  ->each(function ($item) {
                      $item->ordre += 1;
                      $item->save();
                  });
        } else {
            // ✅ Modification d’ordre existant
            if ($nouvelOrdre > $ancienOrdre) {
                $query->whereBetween('ordre', [$ancienOrdre + 1, $nouvelOrdre])
                      ->orderBy('ordre') // ordre croissant
                      ->get()
                      ->each(function ($item) {
                          $item->ordre -= 1;
                          $item->save();
                      });
            } else {
                $query->whereBetween('ordre', [$nouvelOrdre, $ancienOrdre - 1])
                      ->orderBy('ordre', 'desc') // ordre décroissant
                      ->get()
                      ->each(function ($item) {
                          $item->ordre += 1;
                          $item->save();
                      });
            }
        }
    }
    

}