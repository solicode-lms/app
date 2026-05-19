<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgGapp\Models\EMetadataDefinition;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

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
        'reference',
        'name',
        'groupe',
        'type',
        'scope',
        'description',
        'default_value'
    ];



    public function editableFieldsByRoles(): array
    {
        return [
        
        ];
    }


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
        $this->title = __('PkgGapp::eMetadataDefinition.plural');
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
            $eMetadataDefinition = $this->find($data['id']);
            $eMetadataDefinition->fill($data);
        } else {
            $eMetadataDefinition = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($eMetadataDefinition->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $eMetadataDefinition->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($eMetadataDefinition->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($eMetadataDefinition->id, $data);
            }
        }

        return $eMetadataDefinition;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('eMetadataDefinition');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('groupe', $scopeVariables)) {
                    $this->fieldsFilterable[] = [
                        'field' => 'groupe', 
                        'type'  => 'String', 
                        'label' => 'groupe'
                    ];
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



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('eMetadataDefinition_view_type', $default_view_type);
        $eMetadataDefinition_viewType = $this->viewState->get('eMetadataDefinition_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('eMetadataDefinition_view_type') === 'widgets') {
            $this->viewState->set("scope.eMetadataDefinition.visible", 1);
        }else{
            $this->viewState->remove("scope.eMetadataDefinition.visible");
        }
        
        // Récupération des données
        $eMetadataDefinitions_data = $this->paginate($params);
        $eMetadataDefinitions_stats = $this->geteMetadataDefinitionStats();
        $eMetadataDefinitions_total = $this->count();
        $eMetadataDefinitions_filters = $this->getFieldsFilterable();
        $eMetadataDefinition_instance = $this->createInstance();
        $eMetadataDefinition_viewTypes = $this->getViewTypes();
        $eMetadataDefinition_partialViewName = $this->getPartialViewName($eMetadataDefinition_viewType);
        $eMetadataDefinition_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.eMetadataDefinition.stats', $eMetadataDefinitions_stats);
    
        $eMetadataDefinitions_permissions = [

            'edit-eMetadataDefinition' => Auth::user()->can('edit-eMetadataDefinition'),
            'destroy-eMetadataDefinition' => Auth::user()->can('destroy-eMetadataDefinition'),
            'show-eMetadataDefinition' => Auth::user()->can('show-eMetadataDefinition'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $eMetadataDefinitions_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($eMetadataDefinitions_data as $item) {
                $eMetadataDefinitions_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'eMetadataDefinition_viewTypes',
            'eMetadataDefinition_viewType',
            'eMetadataDefinitions_data',
            'eMetadataDefinitions_stats',
            'eMetadataDefinitions_total',
            'eMetadataDefinitions_filters',
            'eMetadataDefinition_instance',
            'eMetadataDefinition_title',
            'contextKey',
            'eMetadataDefinitions_permissions',
            'eMetadataDefinitions_permissionsByItem'
        );
    
        return [
            'eMetadataDefinitions_data' => $eMetadataDefinitions_data,
            'eMetadataDefinitions_stats' => $eMetadataDefinitions_stats,
            'eMetadataDefinitions_total' => $eMetadataDefinitions_total,
            'eMetadataDefinitions_filters' => $eMetadataDefinitions_filters,
            'eMetadataDefinition_instance' => $eMetadataDefinition_instance,
            'eMetadataDefinition_viewType' => $eMetadataDefinition_viewType,
            'eMetadataDefinition_viewTypes' => $eMetadataDefinition_viewTypes,
            'eMetadataDefinition_partialViewName' => $eMetadataDefinition_partialViewName,
            'contextKey' => $contextKey,
            'eMetadataDefinition_compact_value' => $compact_value,
            'eMetadataDefinitions_permissions' => $eMetadataDefinitions_permissions,
            'eMetadataDefinitions_permissionsByItem' => $eMetadataDefinitions_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $eMetadataDefinition_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $eMetadataDefinition_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($eMetadataDefinition_ids as $id) {
            $eMetadataDefinition = $this->find($id);
            $this->authorize('update', $eMetadataDefinition);
    
            $allFields = $this->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $valeursChamps[$field]])
                ->toArray();
    
            if (!empty($data)) {
                $this->updateOnlyExistanteAttribute($id, $data);
            }

            $jobManager->tick();
            
        }

        return "done";
    }

    /**
    * Liste des champs autorisés à l’édition inline
    */
    public function getInlineFieldsEditable(): array
    {
        // Champs considérés comme inline
        $inlineFields = [
            'name',
            'groupe',
            'description'
        ];

        // Récupération des champs autorisés par rôle via getFieldsEditable()
        return array_values(array_intersect(
            $inlineFields,
            $this->getFieldsEditable()
        ));
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(EMetadataDefinition $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgGapp\App\Requests\EMetadataDefinitionRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'e_metadata_definition',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'name':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'groupe':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'description':
                return $this->computeFieldMeta($e, $field, $meta, 'text');

            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(EMetadataDefinition $e, array $changes): EMetadataDefinition
    {
        $allowed = $this->getInlineFieldsEditable();
        $filtered = Arr::only($changes, $allowed);

        if (empty($filtered)) {
            abort(422, 'Aucun champ autorisé.');
        }

        $rules = [];
        foreach ($filtered as $field => $value) {
            $meta = $this->buildFieldMeta($e, $field);
            $rules[$field] = $meta['validation'] ?? ['nullable'];
        }
        
        $e->fill($filtered);
        Validator::make($e->toArray(), $rules)->validate();
        $e = $this->updateOnlyExistanteAttribute($e->id, $filtered);

        return $e;
    }

    /**
     * Formatte les valeurs pour l’affichage inline
     */
    public function formatDisplayValues(EMetadataDefinition $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'name':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'groupe':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'description':
                    $html = view('Core::fields_by_type.text', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;

                default:
                    // fallback générique si champ non pris en charge
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                    ])->render();

                    $out[$field] = ['html' => $html];
            }
        }
        return $out;
    }
}
