<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgEvaluateurs\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgEvaluateurs\Models\Evaluateur;
use Modules\Core\Services\BaseService;

/**
 * Classe EvaluateurService pour gérer la persistance de l'entité Evaluateur.
 */
class BaseEvaluateurService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour evaluateurs.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'prenom',
        'email',
        'organism',
        'telephone',
        'user_id'
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
     * Constructeur de la classe EvaluateurService.
     */
    public function __construct()
    {
        parent::__construct(new Evaluateur());
        $this->fieldsFilterable = [];
        $this->title = __('PkgEvaluateurs::evaluateur.plural');
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
            $evaluateur = $this->find($data['id']);
            $evaluateur->fill($data);
        } else {
            $evaluateur = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($evaluateur->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $evaluateur->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($evaluateur->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($evaluateur->id, $data);
            }
        }

        return $evaluateur;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('evaluateur');
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
            



    }


    /**
     * Crée une nouvelle instance de evaluateur.
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
    public function getEvaluateurStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }


    public function initPassword(int $evaluateurId)
    {
        $evaluateur = $this->find($evaluateurId);
        if (!$evaluateur) {
            return false; 
        }
        $value =  $evaluateur->save();
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
            'table' => 'PkgEvaluateurs::evaluateur._table',
            default => 'PkgEvaluateurs::evaluateur._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('evaluateur_view_type', $default_view_type);
        $evaluateur_viewType = $this->viewState->get('evaluateur_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('evaluateur_view_type') === 'widgets') {
            $this->viewState->set("scope.evaluateur.visible", 1);
        }else{
            $this->viewState->remove("scope.evaluateur.visible");
        }
        
        // Récupération des données
        $evaluateurs_data = $this->paginate($params);
        $evaluateurs_stats = $this->getevaluateurStats();
        $evaluateurs_total = $this->count();
        $evaluateurs_filters = $this->getFieldsFilterable();
        $evaluateur_instance = $this->createInstance();
        $evaluateur_viewTypes = $this->getViewTypes();
        $evaluateur_partialViewName = $this->getPartialViewName($evaluateur_viewType);
        $evaluateur_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.evaluateur.stats', $evaluateurs_stats);
    
        $evaluateurs_permissions = [
            'initPassword-evaluateur' => Auth::user()->can('initPassword-evaluateur'),           
            
            'edit-evaluateur' => Auth::user()->can('edit-evaluateur'),
            'destroy-evaluateur' => Auth::user()->can('destroy-evaluateur'),
            'show-evaluateur' => Auth::user()->can('show-evaluateur'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $evaluateurs_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($evaluateurs_data as $item) {
                $evaluateurs_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'evaluateur_viewTypes',
            'evaluateur_viewType',
            'evaluateurs_data',
            'evaluateurs_stats',
            'evaluateurs_total',
            'evaluateurs_filters',
            'evaluateur_instance',
            'evaluateur_title',
            'contextKey',
            'evaluateurs_permissions',
            'evaluateurs_permissionsByItem'
        );
    
        return [
            'evaluateurs_data' => $evaluateurs_data,
            'evaluateurs_stats' => $evaluateurs_stats,
            'evaluateurs_total' => $evaluateurs_total,
            'evaluateurs_filters' => $evaluateurs_filters,
            'evaluateur_instance' => $evaluateur_instance,
            'evaluateur_viewType' => $evaluateur_viewType,
            'evaluateur_viewTypes' => $evaluateur_viewTypes,
            'evaluateur_partialViewName' => $evaluateur_partialViewName,
            'contextKey' => $contextKey,
            'evaluateur_compact_value' => $compact_value,
            'evaluateurs_permissions' => $evaluateurs_permissions,
            'evaluateurs_permissionsByItem' => $evaluateurs_permissionsByItem
        ];
    }

}
