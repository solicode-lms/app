<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\Services\Base;

use Modules\PkgApprenants\Models\Apprenant;
use Modules\Core\Services\BaseService;

/**
 * Classe ApprenantService pour gérer la persistance de l'entité Apprenant.
 */
class BaseApprenantService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour apprenants.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'nom_arab',
        'prenom',
        'prenom_arab',
        'profile_image',
        'cin',
        'date_naissance',
        'sexe',
        'nationalite_id',
        'lieu_naissance',
        'diplome',
        'adresse',
        'niveaux_scolaire_id',
        'tele_num',
        'user_id',
        'matricule',
        'date_inscription',
        'actif'
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
     * Constructeur de la classe ApprenantService.
     */
    public function __construct()
    {
        parent::__construct(new Apprenant());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprenants::apprenant.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('apprenant');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('groupes', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToManyFilter(__("PkgApprenants::groupe.plural"), 'groupe_id', \Modules\PkgApprenants\Models\Groupe::class, 'code');
        }
        if (!array_key_exists('actif', $scopeVariables)) {
        $this->fieldsFilterable[] = ['field' => 'actif', 'type' => 'Boolean', 'label' => 'actif'];
        }
    }

    /**
     * Crée une nouvelle instance de apprenant.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array|object $data)
    {
        return parent::create($data);
    }

    /**
     * Trie par date de mise à jour si il n'existe aucune trie
     * @param mixed $query
     * @param mixed $sort
     */
    public function applySort($query, $sort)
    {
        if ($sort) {
            return parent::applySort($query, $sort);
        }else{
            return $query->orderBy("updated_at","desc");
        }
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getApprenantStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }


    public function initPassword(int $apprenantId)
    {
        $apprenant = $this->find($apprenantId);
        if (!$apprenant) {
            return false; 
        }
        $value =  $apprenant->save();
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
            'table' => 'PkgApprenants::apprenant._table',
            default => 'PkgApprenants::apprenant._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('apprenant_view_type', $default_view_type);
        $apprenant_viewType = $this->viewState->get('apprenant_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('apprenant_view_type') === 'widgets') {
            $this->viewState->set("filter.apprenant.visible", 1);
        }
        
        // Récupération des données
        $apprenants_data = $this->paginate($params);
        $apprenants_stats = $this->getapprenantStats();
        $apprenants_filters = $this->getFieldsFilterable();
        $apprenant_instance = $this->createInstance();
        $apprenant_viewTypes = $this->getViewTypes();
        $apprenant_partialViewName = $this->getPartialViewName($apprenant_viewType);
        $apprenant_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.apprenant.stats', $apprenants_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'apprenant_viewTypes',
            'apprenant_viewType',
            'apprenants_data',
            'apprenants_stats',
            'apprenants_filters',
            'apprenant_instance',
            'apprenant_title',
            'contextKey'
        );
    
        return [
            'apprenants_data' => $apprenants_data,
            'apprenants_stats' => $apprenants_stats,
            'apprenants_filters' => $apprenants_filters,
            'apprenant_instance' => $apprenant_instance,
            'apprenant_viewType' => $apprenant_viewType,
            'apprenant_viewTypes' => $apprenant_viewTypes,
            'apprenant_partialViewName' => $apprenant_partialViewName,
            'contextKey' => $contextKey,
            'apprenant_compact_value' => $compact_value
        ];
    }

}
