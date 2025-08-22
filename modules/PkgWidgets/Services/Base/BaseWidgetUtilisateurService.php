<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgWidgets\Models\WidgetUtilisateur;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

/**
 * Classe WidgetUtilisateurService pour gérer la persistance de l'entité WidgetUtilisateur.
 */
class BaseWidgetUtilisateurService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour widgetUtilisateurs.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'user_id',
        'widget_id',
        'titre',
        'sous_titre',
        'visible'
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
     * Constructeur de la classe WidgetUtilisateurService.
     */
    public function __construct()
    {
        parent::__construct(new WidgetUtilisateur());
        $this->fieldsFilterable = [];
        $this->title = __('PkgWidgets::widgetUtilisateur.plural');
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
            $widgetUtilisateur = $this->find($data['id']);
            $widgetUtilisateur->fill($data);
        } else {
            $widgetUtilisateur = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($widgetUtilisateur->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $widgetUtilisateur->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($widgetUtilisateur->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($widgetUtilisateur->id, $data);
            }
        }

        return $widgetUtilisateur;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('widgetUtilisateur');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('user_id', $scopeVariables)) {


                    $userService = new \Modules\PkgAutorisation\Services\UserService();
                    $userIds = $this->getAvailableFilterValues('user_id');
                    $users = $userService->getByIds($userIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgAutorisation::user.plural"), 
                        'user_id', 
                        \Modules\PkgAutorisation\Models\User::class, 
                        'name',
                        $users
                    );
                }
            
            
                $sectionWidgetService = new \Modules\PkgWidgets\Services\SectionWidgetService();
                $sectionWidgetIds = $this->getAvailableFilterValues('Widget.Section_widget_id');
                $sectionWidgets = $sectionWidgetService->getByIds($sectionWidgetIds);

                $this->fieldsFilterable[] = $this->generateRelationFilter(
                    __("PkgWidgets::sectionWidget.plural"),
                    'Widget.Section_widget_id', 
                    \Modules\PkgWidgets\Models\SectionWidget::class,
                    "id", 
                    "id",
                    $sectionWidgets
                );
            
            
                if (!array_key_exists('visible', $scopeVariables)) {
                    $this->fieldsFilterable[] = [
                        'field' => 'visible', 
                        'type'  => 'Boolean', 
                        'label' => 'visible'
                    ];
                }
            



    }


    /**
     * Crée une nouvelle instance de widgetUtilisateur.
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
    public function getWidgetUtilisateurStats(): array
    {

        $stats = $this->initStats();

        // Ajouter les statistiques du propriétaire
        //$contexteState = $this->getContextState();
        // if ($contexteState !== null) {
        //     $stats[] = $contexteState;
        // }
        

        return $stats;
    }

    public function getContextState()
    {
        $value = $this->viewState->generateTitleFromVariables();
        return [
                "icon" => "fas fa-filter",
                "label" => "Filtre",
                "value" =>  $value
        ];
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
                'icon'  => 'fas fa-table',
            ],
            [
                'type'  => 'widgets',
                'label' => 'Vue Widgets',
                'icon'  => 'fas fa-th-large',
            ],
        ];
    }

    /**
     * Retourne le nom de la vue partielle selon le type de vue sélectionné
     */
    public function getPartialViewName(string $viewType): string
    {
        return match ($viewType) {
            'table' => 'PkgWidgets::widgetUtilisateur._table',
            'widgets' => 'PkgWidgets::widgetUtilisateur._widgets',
            default => 'PkgWidgets::widgetUtilisateur._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'widgets';
        $this->viewState->setIfEmpty('widgetUtilisateur_view_type', $default_view_type);
        $widgetUtilisateur_viewType = $this->viewState->get('widgetUtilisateur_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('widgetUtilisateur_view_type') === 'widgets') {
            $this->viewState->set("scope.widgetUtilisateur.visible", 1);
        }else{
            $this->viewState->remove("scope.widgetUtilisateur.visible");
        }
        
        // Récupération des données
        $widgetUtilisateurs_data = $this->paginate($params);
        $widgetUtilisateurs_stats = $this->getwidgetUtilisateurStats();
        $widgetUtilisateurs_total = $this->count();
        $widgetUtilisateurs_filters = $this->getFieldsFilterable();
        $widgetUtilisateur_instance = $this->createInstance();
        $widgetUtilisateur_viewTypes = $this->getViewTypes();
        $widgetUtilisateur_partialViewName = $this->getPartialViewName($widgetUtilisateur_viewType);
        $widgetUtilisateur_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.widgetUtilisateur.stats', $widgetUtilisateurs_stats);
    
        $widgetUtilisateurs_permissions = [

            'edit-widgetUtilisateur' => Auth::user()->can('edit-widgetUtilisateur'),
            'destroy-widgetUtilisateur' => Auth::user()->can('destroy-widgetUtilisateur'),
            'show-widgetUtilisateur' => Auth::user()->can('show-widgetUtilisateur'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $widgetUtilisateurs_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($widgetUtilisateurs_data as $item) {
                $widgetUtilisateurs_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'widgetUtilisateur_viewTypes',
            'widgetUtilisateur_viewType',
            'widgetUtilisateurs_data',
            'widgetUtilisateurs_stats',
            'widgetUtilisateurs_total',
            'widgetUtilisateurs_filters',
            'widgetUtilisateur_instance',
            'widgetUtilisateur_title',
            'contextKey',
            'widgetUtilisateurs_permissions',
            'widgetUtilisateurs_permissionsByItem'
        );
    
        return [
            'widgetUtilisateurs_data' => $widgetUtilisateurs_data,
            'widgetUtilisateurs_stats' => $widgetUtilisateurs_stats,
            'widgetUtilisateurs_total' => $widgetUtilisateurs_total,
            'widgetUtilisateurs_filters' => $widgetUtilisateurs_filters,
            'widgetUtilisateur_instance' => $widgetUtilisateur_instance,
            'widgetUtilisateur_viewType' => $widgetUtilisateur_viewType,
            'widgetUtilisateur_viewTypes' => $widgetUtilisateur_viewTypes,
            'widgetUtilisateur_partialViewName' => $widgetUtilisateur_partialViewName,
            'contextKey' => $contextKey,
            'widgetUtilisateur_compact_value' => $compact_value,
            'widgetUtilisateurs_permissions' => $widgetUtilisateurs_permissions,
            'widgetUtilisateurs_permissionsByItem' => $widgetUtilisateurs_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $widgetUtilisateur_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $widgetUtilisateur_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($widgetUtilisateur_ids as $id) {
            $widgetUtilisateur = $this->find($id);
            $this->authorize('update', $widgetUtilisateur);
    
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
    public function getFieldsEditable(): array
    {
        return [
            'ordre',
            'widget_id',
            'package',
            'type',
            'visible'
        ];
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(WidgetUtilisateur $e, string $field): array
    {
        $meta = [
            'entity'         => 'widget_utilisateur',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
        ];

        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgWidgets\App\Requests\WidgetUtilisateurRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }
       switch ($field) {
            case 'ordre':
                return $this->computeFieldMeta($e, $field, $meta, 'number', $validationRules);

            case 'widget_id':
                 $values = (new \Modules\PkgWidgets\Services\WidgetService())
                    ->getAllForSelect($e->widget)
                    ->map(fn($entity) => [
                        'value' => (int) $entity->id,
                        'label' => (string) $entity,
                    ])
                    ->toArray();

                return $this->computeFieldMeta($e, $field, $meta, 'select', $validationRules, [
                    'required' => true,
                    'options'  => [
                        'source' => 'static',
                        'values' => $values,
                    ],
                ]);
            case 'package':
                return $this->computeFieldMeta($e, $field, $meta, 'string', $validationRules);
            case 'type':
                return $this->computeFieldMeta($e, $field, $meta, 'string', $validationRules);
            case 'visible':
                return $this->computeFieldMeta($e, $field, $meta, 'boolean', $validationRules);

            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(WidgetUtilisateur $e, array $changes): WidgetUtilisateur
    {
        $allowed = $this->getFieldsEditable();
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
    public function formatDisplayValues(WidgetUtilisateur $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'ordre':
                    $html = view('Core::fields_by_type.integer', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'ordre'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'widget_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'widget'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'package':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'type':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'visible':
                    $html = view('Core::fields_by_type.boolean', [
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
