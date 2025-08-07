<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
        $this->title = __('PkgGapp::eMetadatum.plural');
    }


    /**
     * Applique les calculs dynamiques sur les champs marqués avec l’attribut `data-calcule`
     * pendant l’édition ou la création d’une entité.
     *
     * Cette méthode est utilisée dans les formulaires dynamiques pour recalculer certains champs
     * (ex : note, barème, état, progression...) en fonction des valeurs saisies ou modifiées.
     *
     * Elle est déclenchée automatiquement lorsqu’un champ du formulaire possède l’attribut `data-calcule`.
     *
     * @param mixed $data Données en cours d’édition (array ou modèle hydraté sans persistance).
     * @return mixed L’entité enrichie avec les champs recalculés.
     */
    public function dataCalcul($data)
    {
        // 🧾 Chargement ou initialisation de l'entité
        if (!empty($data['id'])) {
            $eMetadatum = $this->find($data['id']);
            $eMetadatum->fill($data);
        } else {
            $eMetadatum = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($eMetadatum->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $eMetadatum->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($eMetadatum->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($eMetadatum->id, $data);
            }
        }

        return $eMetadatum;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('eMetadatum');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('e_model_id', $scopeVariables)) {


                    $eModelService = new \Modules\PkgGapp\Services\EModelService();
                    $eModelIds = $this->getAvailableFilterValues('e_model_id');
                    $eModels = $eModelService->getByIds($eModelIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgGapp::eModel.plural"), 
                        'e_model_id', 
                        \Modules\PkgGapp\Models\EModel::class, 
                        'name',
                        $eModels
                    );
                }
            
            
                if (!array_key_exists('e_data_field_id', $scopeVariables)) {


                    $eDataFieldService = new \Modules\PkgGapp\Services\EDataFieldService();
                    $eDataFieldIds = $this->getAvailableFilterValues('e_data_field_id');
                    $eDataFields = $eDataFieldService->getByIds($eDataFieldIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgGapp::eDataField.plural"), 
                        'e_data_field_id', 
                        \Modules\PkgGapp\Models\EDataField::class, 
                        'name',
                        $eDataFields
                    );
                }
            
            
                if (!array_key_exists('e_metadata_definition_id', $scopeVariables)) {


                    $eMetadataDefinitionService = new \Modules\PkgGapp\Services\EMetadataDefinitionService();
                    $eMetadataDefinitionIds = $this->getAvailableFilterValues('e_metadata_definition_id');
                    $eMetadataDefinitions = $eMetadataDefinitionService->getByIds($eMetadataDefinitionIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgGapp::eMetadataDefinition.plural"), 
                        'e_metadata_definition_id', 
                        \Modules\PkgGapp\Models\EMetadataDefinition::class, 
                        'name',
                        $eMetadataDefinitions
                    );
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



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('eMetadatum_view_type', $default_view_type);
        $eMetadatum_viewType = $this->viewState->get('eMetadatum_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('eMetadatum_view_type') === 'widgets') {
            $this->viewState->set("scope.eMetadatum.visible", 1);
        }else{
            $this->viewState->remove("scope.eMetadatum.visible");
        }
        
        // Récupération des données
        $eMetadata_data = $this->paginate($params);
        $eMetadata_stats = $this->geteMetadatumStats();
        $eMetadata_total = $this->count();
        $eMetadata_filters = $this->getFieldsFilterable();
        $eMetadatum_instance = $this->createInstance();
        $eMetadatum_viewTypes = $this->getViewTypes();
        $eMetadatum_partialViewName = $this->getPartialViewName($eMetadatum_viewType);
        $eMetadatum_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.eMetadatum.stats', $eMetadata_stats);
    
        $eMetadata_permissions = [

            'edit-eMetadatum' => Auth::user()->can('edit-eMetadatum'),
            'destroy-eMetadatum' => Auth::user()->can('destroy-eMetadatum'),
            'show-eMetadatum' => Auth::user()->can('show-eMetadatum'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $eMetadata_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($eMetadata_data as $item) {
                $eMetadata_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'eMetadatum_viewTypes',
            'eMetadatum_viewType',
            'eMetadata_data',
            'eMetadata_stats',
            'eMetadata_total',
            'eMetadata_filters',
            'eMetadatum_instance',
            'eMetadatum_title',
            'contextKey',
            'eMetadata_permissions',
            'eMetadata_permissionsByItem'
        );
    
        return [
            'eMetadata_data' => $eMetadata_data,
            'eMetadata_stats' => $eMetadata_stats,
            'eMetadata_total' => $eMetadata_total,
            'eMetadata_filters' => $eMetadata_filters,
            'eMetadatum_instance' => $eMetadatum_instance,
            'eMetadatum_viewType' => $eMetadatum_viewType,
            'eMetadatum_viewTypes' => $eMetadatum_viewTypes,
            'eMetadatum_partialViewName' => $eMetadatum_partialViewName,
            'contextKey' => $contextKey,
            'eMetadatum_compact_value' => $compact_value,
            'eMetadata_permissions' => $eMetadata_permissions,
            'eMetadata_permissionsByItem' => $eMetadata_permissionsByItem
        ];
    }

}
