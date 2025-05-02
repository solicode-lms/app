<?php

namespace Modules\Core\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Services\Contracts\ServiceInterface;

use Modules\Core\Services\Traits\{
    CrudCreateTrait,
    CrudDeleteTrait,
    CrudEditTrait,
    CrudReadTrait,
    MessageTrait,
    PaginateTrait,
    QueryBuilderTrait,
    CrudTrait,
    CrudUpdateTrait,
    RelationTrait,
    FilterTrait,
    SortTrait,
    StatsTrait
};
use Modules\PkgNotification\Services\NotificationService;

/**
 * Classe abstraite BaseService qui fournit une implémentation de base
 * pour les opérations courantes de manipulation des données.
 */
abstract class BaseService implements ServiceInterface
{

    use 
        MessageTrait,
        PaginateTrait, 
        SortTrait,
        QueryBuilderTrait, 
        CrudTrait, 
        CrudReadTrait, 
        CrudCreateTrait, 
        CrudUpdateTrait, 
        CrudDeleteTrait, 
        CrudEditTrait, 
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

    public $userHasSentFilter = false;

    /**
     * Le titre à afficher dnas la page Index
     *
     * @var [type]
     */
    protected $title;

    /** Configuration pour afficher CRUD sur une source donnée Query à partie d'une méthode 
     * qui retourne un objet de type builder
     */
    protected $dataSources = [];

    /**
     * Colonne utilisée pour grouper les enregistrements lors du tri par ordre.
     * Exemple : 'projet_id' pour les tâches.
     */
    protected $ordreGroupColumn = null;

    /**
     * Méthode abstraite pour obtenir les champs recherchables.
     *
     * @return array
     */
    abstract public function getFieldsSearchable(): array;


    public function getFieldsEditable(): array
    {
        return $this->fieldsSearchable;
    }
    
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
        return array_merge($this->fieldsSearchable, $dynamicAttributes, ['created_at', 'updated_at']);
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

    /**
     * Summary of reorderOrdreColumn
     * @param mixed $ancienOrdre
     * @param int $nouvelOrdre
     * @param int $idEnCoursModification : pour ne pas changer l'ordre de l'objet en cours de modification
     * @param mixed $groupValue
     * @return void
     */
    protected function reorderOrdreColumn(?int $ancienOrdre, int $nouvelOrdre, int $idEnCoursModification = null, $groupValue = null): void
    {
        $this->normalizeOrdreIfNeeded($groupValue);

        if ($ancienOrdre !== null && $nouvelOrdre === $ancienOrdre) {
            return;
        }

        $query = $this->model->newQuery();

        if ($idEnCoursModification !== null) {
            $query->where('id', '!=', $idEnCoursModification);
        }

        // ✅ Appliquer la contrainte de groupe si nécessaire
        if ($this->ordreGroupColumn && $groupValue !== null) {
            $query->where($this->ordreGroupColumn, $groupValue);
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

    

    protected function normalizeOrdreIfNeeded($groupValue = null): void
    {
        $query = $this->model->newQuery();
    
        if ($this->ordreGroupColumn && $groupValue !== null) {
            $query->where($this->ordreGroupColumn, $groupValue);
        }
    
        $elementsSansOrdre = $query->where(function($q){
                                    $q->whereNull('ordre')->orWhere('ordre', '');
                                })
                                ->orderBy('id')
                                ->get();
    
        if ($elementsSansOrdre->isEmpty()) {
            return;
        }
    
        // Trouver l'ordre maximal actuel dans le groupe
        $maxOrdreQuery = $this->model->newQuery();
        if ($this->ordreGroupColumn && $groupValue !== null) {
            $maxOrdreQuery->where($this->ordreGroupColumn, $groupValue);
        }
        $maxOrdre = $maxOrdreQuery->max('ordre') ?? 0;
    
        foreach ($elementsSansOrdre as $element) {
            $maxOrdre++;
            $element->ordre = $maxOrdre;
            $element->save();
        }
    }
    

}