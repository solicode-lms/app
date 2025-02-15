<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\Services\Base;

use Modules\PkgWidgets\Models\WidgetType;
use Modules\Core\Services\BaseService;

/**
 * Classe WidgetTypeService pour gérer la persistance de l'entité WidgetType.
 */
class BaseWidgetTypeService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour widgetTypes.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'type',
        'description'
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
     * Constructeur de la classe WidgetTypeService.
     */
    public function __construct()
    {
        parent::__construct(new WidgetType());
        $this->fieldsFilterable = [];
    }

    public function initFieldsFilterable(){
       // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];
    }

    /**
     * Crée une nouvelle instance de widgetType.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        return parent::create($data);
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getWidgetTypeStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }

}
