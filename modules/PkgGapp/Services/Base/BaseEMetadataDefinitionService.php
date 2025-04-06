<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Modules\PkgGapp\Models\EMetadataDefinition;
use Modules\Core\Services\BaseService;

/**
 * Classe EMetadataDefinitionService pour gérer la persistance de l'entité EMetadataDefinition.
 */
class BaseEMetadataDefinitionService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour eMetadataDefinitions.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'groupe',
        'type',
        'scope',
        'description',
        'default_value'
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
     * Constructeur de la classe EMetadataDefinitionService.
     */
    public function __construct()
    {
        parent::__construct(new EMetadataDefinition());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('eMetadataDefinition');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('groupe', $scopeVariables)) {
        $this->fieldsFilterable[] = ['field' => 'groupe', 'type' => 'String', 'label' => 'groupe'];
        }
    }

    /**
     * Crée une nouvelle instance de eMetadataDefinition.
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
    public function getEMetadataDefinitionStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }




    /**
     * Retourne les types de vues disponibles pour l'index (ex: table, widgets...)
     */
    public function getViewTypes(): array
    {
        return [
            [
                'type'  => 'table',
                'label' => 'Vue Tableau',
                'icon'  => 'fa-table',
            ],
        ];
    }

    /**
     * Retourne le nom de la vue partielle selon le type de vue sélectionné
     */
    public function getPartialViewName(string $viewType): string
    {
        return match ($viewType) {
            'table' => 'PkgGapp::eMetadataDefinition._table',
            default => 'PkgGapp::eMetadataDefinition._table',
        };
    }

}
