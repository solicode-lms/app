<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Modules\PkgGapp\Models\EMetadatum;
use Modules\Core\Services\BaseService;

/**
 * Classe EMetadatumService pour gérer la persistance de l'entité EMetadatum.
 */
class BaseEMetadatumService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour eMetadata.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'value_boolean',
        'value_string',
        'value_integer',
        'value_float',
        'value_date',
        'value_datetime',
        'value_enum',
        'value_json',
        'value_text',
        'e_model_id',
        'e_data_field_id',
        'e_metadata_definition_id'
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
     * Constructeur de la classe EMetadatumService.
     */
    public function __construct()
    {
        parent::__construct(new EMetadatum());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('eMetadatum');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('e_model_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGapp::eModel.plural"), 'e_model_id', \Modules\PkgGapp\Models\EModel::class, 'name');
        }
        if (!array_key_exists('e_data_field_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGapp::eDataField.plural"), 'e_data_field_id', \Modules\PkgGapp\Models\EDataField::class, 'name');
        }
        if (!array_key_exists('e_metadata_definition_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGapp::eMetadataDefinition.plural"), 'e_metadata_definition_id', \Modules\PkgGapp\Models\EMetadataDefinition::class, 'name');
        }
    }

    /**
     * Crée une nouvelle instance de eMetadatum.
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
    public function getEMetadatumStats(): array
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
            'table' => 'PkgGapp::eMetadatum._table',
            default => 'PkgGapp::eMetadatum._table',
        };
    }

}
