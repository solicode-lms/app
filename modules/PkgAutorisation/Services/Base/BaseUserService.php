<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutorisation\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgAutorisation\Models\User;
use Modules\Core\Services\BaseService;

/**
 * Classe UserService pour gérer la persistance de l'entité User.
 */
class BaseUserService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour users.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'must_change_password',
        'remember_token'
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
     * Constructeur de la classe UserService.
     */
    public function __construct()
    {
        parent::__construct(new User());
        $this->fieldsFilterable = [];
        $this->title = __('PkgAutorisation::user.plural');
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
            $user = $this->find($data['id']);
            $user->fill($data);
        } else {
            $user = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($user->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $user->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($user->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($user->id, $data);
            }
        }

        return $user;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('user');
        $this->fieldsFilterable = [];
        



    }


    /**
     * Crée une nouvelle instance de user.
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
    public function getUserStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }


    public function initPassword(int $userId)
    {
        $user = $this->find($userId);
        if (!$user) {
            return false; 
        }
        $value =  $user->save();
        $this->pushServiceMessage("info","Traitement title", "message : résultat de traitement");
        return $value;
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
            'table' => 'PkgAutorisation::user._table',
            default => 'PkgAutorisation::user._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('user_view_type', $default_view_type);
        $user_viewType = $this->viewState->get('user_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('user_view_type') === 'widgets') {
            $this->viewState->set("scope.user.visible", 1);
        }else{
            $this->viewState->remove("scope.user.visible");
        }
        
        // Récupération des données
        $users_data = $this->paginate($params);
        $users_stats = $this->getuserStats();
        $users_total = $this->count();
        $users_filters = $this->getFieldsFilterable();
        $user_instance = $this->createInstance();
        $user_viewTypes = $this->getViewTypes();
        $user_partialViewName = $this->getPartialViewName($user_viewType);
        $user_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.user.stats', $users_stats);
    
        $users_permissions = [
            'initPassword-user' => Auth::user()->can('initPassword-user'),           
            
            'edit-user' => Auth::user()->can('edit-user'),
            'destroy-user' => Auth::user()->can('destroy-user'),
            'show-user' => Auth::user()->can('show-user'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $users_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($users_data as $item) {
                $users_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'user_viewTypes',
            'user_viewType',
            'users_data',
            'users_stats',
            'users_total',
            'users_filters',
            'user_instance',
            'user_title',
            'contextKey',
            'users_permissions',
            'users_permissionsByItem'
        );
    
        return [
            'users_data' => $users_data,
            'users_stats' => $users_stats,
            'users_total' => $users_total,
            'users_filters' => $users_filters,
            'user_instance' => $user_instance,
            'user_viewType' => $user_viewType,
            'user_viewTypes' => $user_viewTypes,
            'user_partialViewName' => $user_partialViewName,
            'contextKey' => $contextKey,
            'user_compact_value' => $compact_value,
            'users_permissions' => $users_permissions,
            'users_permissionsByItem' => $users_permissionsByItem
        ];
    }

}
