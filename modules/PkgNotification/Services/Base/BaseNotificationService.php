<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgNotification\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgNotification\Models\Notification;
use Modules\Core\Services\BaseService;

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


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('notification');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('is_read', $scopeVariables)) {
        $this->fieldsFilterable[] = ['field' => 'is_read', 'type' => 'Boolean', 'label' => 'is_read'];
        }

        if (!array_key_exists('user_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutorisation::user.plural"), 'user_id', \Modules\PkgAutorisation\Models\User::class, 'name');
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

}
