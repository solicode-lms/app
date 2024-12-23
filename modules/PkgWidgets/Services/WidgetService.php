<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\Services;

use Modules\PkgWidgets\Models\Widget;
use Modules\Core\Services\BaseService;

/**
 * Classe WidgetService pour gérer la persistance de l'entité Widget.
 */
class WidgetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour widgets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'type_id',
        'model_id',
        'operation_id',
        'color',
        'icon',
        'label',
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
    }

    /**
     * Crée une nouvelle instance de widget.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        return parent::create($data);
    }
}
