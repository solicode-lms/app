<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgApprentissage\Models\RealisationUa;
use Modules\Core\Services\BaseService;

/**
 * Classe RealisationUaService pour gérer la persistance de l'entité RealisationUa.
 */
class BaseRealisationUaService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour realisationUas.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'realisation_micro_competence_id',
        'unite_apprentissage_id',
        'etat_realisation_ua_id',
        'progression_cache',
        'note_cache',
        'bareme_cache',
        'date_debut',
        'date_fin',
        'commentaire_formateur'
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
     * Constructeur de la classe RealisationUaService.
     */
    public function __construct()
    {
        parent::__construct(new RealisationUa());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprentissage::realisationUa.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationUa');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('realisation_micro_competence_id', $scopeVariables)) {
                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgApprentissage::realisationMicroCompetence.plural"), 
                        'realisation_micro_competence_id', 
                        \Modules\PkgApprentissage\Models\RealisationMicroCompetence::class, 
                        'id'
                    );
                }
            
            
                if (!array_key_exists('unite_apprentissage_id', $scopeVariables)) {
                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCompetences::uniteApprentissage.plural"), 
                        'unite_apprentissage_id', 
                        \Modules\PkgCompetences\Models\UniteApprentissage::class, 
                        'code'
                    );
                }
            
            
                if (!array_key_exists('etat_realisation_ua_id', $scopeVariables)) {
                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgApprentissage::etatRealisationUa.plural"), 
                        'etat_realisation_ua_id', 
                        \Modules\PkgApprentissage\Models\EtatRealisationUa::class, 
                        'nom'
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de realisationUa.
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
    public function getRealisationUaStats(): array
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
            'table' => 'PkgApprentissage::realisationUa._table',
            default => 'PkgApprentissage::realisationUa._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('realisationUa_view_type', $default_view_type);
        $realisationUa_viewType = $this->viewState->get('realisationUa_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('realisationUa_view_type') === 'widgets') {
            $this->viewState->set("scope.realisationUa.visible", 1);
        }else{
            $this->viewState->remove("scope.realisationUa.visible");
        }
        
        // Récupération des données
        $realisationUas_data = $this->paginate($params);
        $realisationUas_stats = $this->getrealisationUaStats();
        $realisationUas_filters = $this->getFieldsFilterable();
        $realisationUa_instance = $this->createInstance();
        $realisationUa_viewTypes = $this->getViewTypes();
        $realisationUa_partialViewName = $this->getPartialViewName($realisationUa_viewType);
        $realisationUa_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.realisationUa.stats', $realisationUas_stats);
    
        $realisationUas_permissions = [

            'edit-realisationUa' => Auth::user()->can('edit-realisationUa'),
            'destroy-realisationUa' => Auth::user()->can('destroy-realisationUa'),
            'show-realisationUa' => Auth::user()->can('show-realisationUa'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $realisationUas_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($realisationUas_data as $item) {
                $realisationUas_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'realisationUa_viewTypes',
            'realisationUa_viewType',
            'realisationUas_data',
            'realisationUas_stats',
            'realisationUas_filters',
            'realisationUa_instance',
            'realisationUa_title',
            'contextKey',
            'realisationUas_permissions',
            'realisationUas_permissionsByItem'
        );
    
        return [
            'realisationUas_data' => $realisationUas_data,
            'realisationUas_stats' => $realisationUas_stats,
            'realisationUas_filters' => $realisationUas_filters,
            'realisationUa_instance' => $realisationUa_instance,
            'realisationUa_viewType' => $realisationUa_viewType,
            'realisationUa_viewTypes' => $realisationUa_viewTypes,
            'realisationUa_partialViewName' => $realisationUa_partialViewName,
            'contextKey' => $contextKey,
            'realisationUa_compact_value' => $compact_value,
            'realisationUas_permissions' => $realisationUas_permissions,
            'realisationUas_permissionsByItem' => $realisationUas_permissionsByItem
        ];
    }

}
