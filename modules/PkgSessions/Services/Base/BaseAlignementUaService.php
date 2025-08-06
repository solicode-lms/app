<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgSessions\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgSessions\Models\AlignementUa;
use Modules\Core\Services\BaseService;

/**
 * Classe AlignementUaService pour gérer la persistance de l'entité AlignementUa.
 */
class BaseAlignementUaService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour alignementUas.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'unite_apprentissage_id',
        'session_formation_id',
        'description'
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
     * Constructeur de la classe AlignementUaService.
     */
    public function __construct()
    {
        parent::__construct(new AlignementUa());
        $this->fieldsFilterable = [];
        $this->title = __('PkgSessions::alignementUa.plural');
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
            $realisationTache = $this->find($data['id']);
            $realisationTache->fill($data);
        } else {
            $realisationTache = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($realisationTache->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $realisationTache->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($realisationTache->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($realisationTache->id, $data);
            }
        }

        return $realisationTache;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('alignementUa');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('unite_apprentissage_id', $scopeVariables)) {


                    $uniteApprentissageService = new \Modules\PkgCompetences\Services\UniteApprentissageService();
                    $uniteApprentissageIds = $this->getAvailableFilterValues('unite_apprentissage_id');
                    $uniteApprentissages = $uniteApprentissageService->getByIds($uniteApprentissageIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCompetences::uniteApprentissage.plural"), 
                        'unite_apprentissage_id', 
                        \Modules\PkgCompetences\Models\UniteApprentissage::class, 
                        'code',
                        $uniteApprentissages
                    );
                }
            
            
                if (!array_key_exists('session_formation_id', $scopeVariables)) {


                    $sessionFormationService = new \Modules\PkgSessions\Services\SessionFormationService();
                    $sessionFormationIds = $this->getAvailableFilterValues('session_formation_id');
                    $sessionFormations = $sessionFormationService->getByIds($sessionFormationIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgSessions::sessionFormation.plural"), 
                        'session_formation_id', 
                        \Modules\PkgSessions\Models\SessionFormation::class, 
                        'titre',
                        $sessionFormations
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de alignementUa.
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
    public function getAlignementUaStats(): array
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
            'table' => 'PkgSessions::alignementUa._table',
            default => 'PkgSessions::alignementUa._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('alignementUa_view_type', $default_view_type);
        $alignementUa_viewType = $this->viewState->get('alignementUa_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('alignementUa_view_type') === 'widgets') {
            $this->viewState->set("scope.alignementUa.visible", 1);
        }else{
            $this->viewState->remove("scope.alignementUa.visible");
        }
        
        // Récupération des données
        $alignementUas_data = $this->paginate($params);
        $alignementUas_stats = $this->getalignementUaStats();
        $alignementUas_total = $this->count();
        $alignementUas_filters = $this->getFieldsFilterable();
        $alignementUa_instance = $this->createInstance();
        $alignementUa_viewTypes = $this->getViewTypes();
        $alignementUa_partialViewName = $this->getPartialViewName($alignementUa_viewType);
        $alignementUa_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.alignementUa.stats', $alignementUas_stats);
    
        $alignementUas_permissions = [

            'edit-alignementUa' => Auth::user()->can('edit-alignementUa'),
            'destroy-alignementUa' => Auth::user()->can('destroy-alignementUa'),
            'show-alignementUa' => Auth::user()->can('show-alignementUa'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $alignementUas_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($alignementUas_data as $item) {
                $alignementUas_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'alignementUa_viewTypes',
            'alignementUa_viewType',
            'alignementUas_data',
            'alignementUas_stats',
            'alignementUas_total',
            'alignementUas_filters',
            'alignementUa_instance',
            'alignementUa_title',
            'contextKey',
            'alignementUas_permissions',
            'alignementUas_permissionsByItem'
        );
    
        return [
            'alignementUas_data' => $alignementUas_data,
            'alignementUas_stats' => $alignementUas_stats,
            'alignementUas_total' => $alignementUas_total,
            'alignementUas_filters' => $alignementUas_filters,
            'alignementUa_instance' => $alignementUa_instance,
            'alignementUa_viewType' => $alignementUa_viewType,
            'alignementUa_viewTypes' => $alignementUa_viewTypes,
            'alignementUa_partialViewName' => $alignementUa_partialViewName,
            'contextKey' => $contextKey,
            'alignementUa_compact_value' => $compact_value,
            'alignementUas_permissions' => $alignementUas_permissions,
            'alignementUas_permissionsByItem' => $alignementUas_permissionsByItem
        ];
    }

}
