<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgNotification\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgNotification\Models\Notification;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe NotificationService pour gérer la persistance de l'entité Notification.
 */
class BaseNotificationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour notifications.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'title',
        'type',
        'message',
        'sent_at',
        'is_read',
        'user_id',
        'data'
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
     * Constructeur de la classe NotificationService.
     */
    public function __construct()
    {
        parent::__construct(new Notification());
        $this->fieldsFilterable = [];
        $this->title = __('PkgNotification::notification.plural');
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
            $notification = $this->find($data['id']);
            $notification->fill($data);
        } else {
            $notification = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($notification->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $notification->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($notification->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($notification->id, $data);
            }
        }

        return $notification;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('notification');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('is_read', $scopeVariables)) {
                    $this->fieldsFilterable[] = [
                        'field' => 'is_read', 
                        'type'  => 'Boolean', 
                        'label' => 'is_read'
                    ];
                }
            
            
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
            



    }


    /**
     * Crée une nouvelle instance de notification.
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
    public function getNotificationStats(): array
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
            'table' => 'PkgNotification::notification._table',
            default => 'PkgNotification::notification._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('notification_view_type', $default_view_type);
        $notification_viewType = $this->viewState->get('notification_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('notification_view_type') === 'widgets') {
            $this->viewState->set("scope.notification.visible", 1);
        }else{
            $this->viewState->remove("scope.notification.visible");
        }
        
        // Récupération des données
        $notifications_data = $this->paginate($params);
        $notifications_stats = $this->getnotificationStats();
        $notifications_total = $this->count();
        $notifications_filters = $this->getFieldsFilterable();
        $notification_instance = $this->createInstance();
        $notification_viewTypes = $this->getViewTypes();
        $notification_partialViewName = $this->getPartialViewName($notification_viewType);
        $notification_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.notification.stats', $notifications_stats);
    
        $notifications_permissions = [

            'edit-notification' => Auth::user()->can('edit-notification'),
            'destroy-notification' => Auth::user()->can('destroy-notification'),
            'show-notification' => Auth::user()->can('show-notification'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $notifications_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($notifications_data as $item) {
                $notifications_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'notification_viewTypes',
            'notification_viewType',
            'notifications_data',
            'notifications_stats',
            'notifications_total',
            'notifications_filters',
            'notification_instance',
            'notification_title',
            'contextKey',
            'notifications_permissions',
            'notifications_permissionsByItem'
        );
    
        return [
            'notifications_data' => $notifications_data,
            'notifications_stats' => $notifications_stats,
            'notifications_total' => $notifications_total,
            'notifications_filters' => $notifications_filters,
            'notification_instance' => $notification_instance,
            'notification_viewType' => $notification_viewType,
            'notification_viewTypes' => $notification_viewTypes,
            'notification_partialViewName' => $notification_partialViewName,
            'contextKey' => $contextKey,
            'notification_compact_value' => $compact_value,
            'notifications_permissions' => $notifications_permissions,
            'notifications_permissionsByItem' => $notifications_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $notification_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $notification_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($notification_ids as $id) {
            $notification = $this->find($id);
            $this->authorize('update', $notification);
    
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
            'title',
            'message',
            'sent_at'
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
    public function buildFieldMeta(Notification $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgNotification\App\Requests\NotificationRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'notification',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'title':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'message':
                return $this->computeFieldMeta($e, $field, $meta, 'text');

            case 'sent_at':
                return $this->computeFieldMeta($e, $field, $meta, 'date', $validationRules);
            
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(Notification $e, array $changes): Notification
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
    public function formatDisplayValues(Notification $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'title':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'message':
                    $html = view('Core::fields_by_type.text', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'sent_at':
                    $html = view('Core::fields_by_type.date', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'duree'
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
