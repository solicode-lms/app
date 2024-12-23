<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\Services;

use Modules\PkgWidgets\Models\WidgetOperation;
use Modules\Core\Services\BaseService;

/**
 * Classe WidgetOperationService pour gérer la persistance de l'entité WidgetOperation.
 */
class WidgetOperationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour widgetOperations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'operation',
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
     * Constructeur de la classe WidgetOperationService.
     */
    public function __construct()
    {
        parent::__construct(new WidgetOperation());
    }

    /**
     * Crée une nouvelle instance de widgetOperation.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        return parent::create($data);
    }
}
