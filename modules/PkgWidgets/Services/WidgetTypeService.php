<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\Services;

use Modules\PkgWidgets\Models\WidgetType;
use Modules\Core\Services\BaseService;

/**
 * Classe WidgetTypeService pour gérer la persistance de l'entité WidgetType.
 */
class WidgetTypeService extends BaseService
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
}
