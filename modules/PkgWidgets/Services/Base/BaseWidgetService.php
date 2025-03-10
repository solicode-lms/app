<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\Services\Base;

use Modules\PkgWidgets\Models\Widget;
use Modules\Core\Services\BaseService;

/**
 * Classe WidgetService pour gérer la persistance de l'entité Widget.
 */
class BaseWidgetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour widgets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'label',
        'model_id',
        'type_id',
        'operation_id',
        'color',
        'icon',
        'parameters'
    ];

    /**
     * Renvoie les champs de recherche disponibles.
     *
     * @return array
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldsSearchable;
    }

    /**
     * Constructeur de la classe WidgetService.
     */
    public function __construct()
    {
        parent::__construct(new Widget());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('widget');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('model_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("Core::sysModel.plural"), 'model_id', \Modules\Core\Models\SysModel::class, 'name');
        }
        if (!array_key_exists('type_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgWidgets::widgetType.plural"), 'type_id', \Modules\PkgWidgets\Models\WidgetType::class, 'type');
        }
        if (!array_key_exists('operation_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgWidgets::widgetOperation.plural"), 'operation_id', \Modules\PkgWidgets\Models\WidgetOperation::class, 'id');
        }
    }

    /**
     * Crée une nouvelle instance de widget.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array|object $data)
    {
        return parent::create($data);
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getWidgetStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }



}
