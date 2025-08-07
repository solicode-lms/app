<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Models\Feature;
use Modules\Core\Services\BaseService;

/**
 * Classe FeatureService pour gérer la persistance de l'entité Feature.
 */
class BaseFeatureService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour features.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'description',
        'feature_domain_id'
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
     * Constructeur de la classe FeatureService.
     */
    public function __construct()
    {
        parent::__construct(new Feature());
        $this->fieldsFilterable = [];
        $this->title = __('Core::feature.plural');
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
            $feature = $this->find($data['id']);
            $feature->fill($data);
        } else {
            $feature = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($feature->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $feature->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($feature->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($feature->id, $data);
            }
        }

        return $feature;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('feature');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('feature_domain_id', $scopeVariables)) {


                    $featureDomainService = new \Modules\Core\Services\FeatureDomainService();
                    $featureDomainIds = $this->getAvailableFilterValues('feature_domain_id');
                    $featureDomains = $featureDomainService->getByIds($featureDomainIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("Core::featureDomain.plural"), 
                        'feature_domain_id', 
                        \Modules\Core\Models\FeatureDomain::class, 
                        'name',
                        $featureDomains
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de feature.
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
    public function getFeatureStats(): array
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
            'table' => 'Core::feature._table',
            default => 'Core::feature._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('feature_view_type', $default_view_type);
        $feature_viewType = $this->viewState->get('feature_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('feature_view_type') === 'widgets') {
            $this->viewState->set("scope.feature.visible", 1);
        }else{
            $this->viewState->remove("scope.feature.visible");
        }
        
        // Récupération des données
        $features_data = $this->paginate($params);
        $features_stats = $this->getfeatureStats();
        $features_total = $this->count();
        $features_filters = $this->getFieldsFilterable();
        $feature_instance = $this->createInstance();
        $feature_viewTypes = $this->getViewTypes();
        $feature_partialViewName = $this->getPartialViewName($feature_viewType);
        $feature_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.feature.stats', $features_stats);
    
        $features_permissions = [

            'edit-feature' => Auth::user()->can('edit-feature'),
            'destroy-feature' => Auth::user()->can('destroy-feature'),
            'show-feature' => Auth::user()->can('show-feature'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $features_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($features_data as $item) {
                $features_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'feature_viewTypes',
            'feature_viewType',
            'features_data',
            'features_stats',
            'features_total',
            'features_filters',
            'feature_instance',
            'feature_title',
            'contextKey',
            'features_permissions',
            'features_permissionsByItem'
        );
    
        return [
            'features_data' => $features_data,
            'features_stats' => $features_stats,
            'features_total' => $features_total,
            'features_filters' => $features_filters,
            'feature_instance' => $feature_instance,
            'feature_viewType' => $feature_viewType,
            'feature_viewTypes' => $feature_viewTypes,
            'feature_partialViewName' => $feature_partialViewName,
            'contextKey' => $contextKey,
            'feature_compact_value' => $compact_value,
            'features_permissions' => $features_permissions,
            'features_permissionsByItem' => $features_permissionsByItem
        ];
    }

}
