<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutorisation\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgAutorisation\Models\Profile;
use Modules\Core\Services\BaseService;

/**
 * Classe ProfileService pour gérer la persistance de l'entité Profile.
 */
class BaseProfileService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour profiles.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'user_id',
        'phone',
        'address',
        'profile_picture',
        'bio'
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
     * Constructeur de la classe ProfileService.
     */
    public function __construct()
    {
        parent::__construct(new Profile());
        $this->fieldsFilterable = [];
        $this->title = __('PkgAutorisation::profile.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('profile');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('user_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutorisation::user.plural"), 'user_id', \Modules\PkgAutorisation\Models\User::class, 'name');
        }

    }

    /**
     * Crée une nouvelle instance de profile.
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
    public function getProfileStats(): array
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
            'table' => 'PkgAutorisation::profile._table',
            default => 'PkgAutorisation::profile._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('profile_view_type', $default_view_type);
        $profile_viewType = $this->viewState->get('profile_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('profile_view_type') === 'widgets') {
            $this->viewState->set("scope.profile.visible", 1);
        }else{
            $this->viewState->remove("scope.profile.visible");
        }
        
        // Récupération des données
        $profiles_data = $this->paginate($params);
        $profiles_stats = $this->getprofileStats();
        $profiles_filters = $this->getFieldsFilterable();
        $profile_instance = $this->createInstance();
        $profile_viewTypes = $this->getViewTypes();
        $profile_partialViewName = $this->getPartialViewName($profile_viewType);
        $profile_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.profile.stats', $profiles_stats);
    
        $profiles_permissions = [

            'edit-profile' => Auth::user()->can('edit-profile'),
            'destroy-profile' => Auth::user()->can('destroy-profile'),
            'show-profile' => Auth::user()->can('show-profile'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $profiles_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($profiles_data as $item) {
                $profiles_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'profile_viewTypes',
            'profile_viewType',
            'profiles_data',
            'profiles_stats',
            'profiles_filters',
            'profile_instance',
            'profile_title',
            'contextKey',
            'profiles_permissions',
            'profiles_permissionsByItem'
        );
    
        return [
            'profiles_data' => $profiles_data,
            'profiles_stats' => $profiles_stats,
            'profiles_filters' => $profiles_filters,
            'profile_instance' => $profile_instance,
            'profile_viewType' => $profile_viewType,
            'profile_viewTypes' => $profile_viewTypes,
            'profile_partialViewName' => $profile_partialViewName,
            'contextKey' => $contextKey,
            'profile_compact_value' => $compact_value,
            'profiles_permissions' => $profiles_permissions,
            'profiles_permissionsByItem' => $profiles_permissionsByItem
        ];
    }

}
