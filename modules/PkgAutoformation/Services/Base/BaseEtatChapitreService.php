<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgAutoformation\Models\EtatChapitre;
use Modules\Core\Services\BaseService;

/**
 * Classe EtatChapitreService pour gérer la persistance de l'entité EtatChapitre.
 */
class BaseEtatChapitreService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour etatChapitres.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'workflow_chapitre_id',
        'sys_color_id',
        'is_editable_only_by_formateur',
        'description',
        'formateur_id'
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
     * Constructeur de la classe EtatChapitreService.
     */
    public function __construct()
    {
        parent::__construct(new EtatChapitre());
        $this->fieldsFilterable = [];
        $this->title = __('PkgAutoformation::etatChapitre.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('etatChapitre');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('workflow_chapitre_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutoformation::workflowChapitre.plural"), 'workflow_chapitre_id', \Modules\PkgAutoformation\Models\WorkflowChapitre::class, 'code');
        }

        if (!array_key_exists('sys_color_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("Core::sysColor.plural"), 'sys_color_id', \Modules\Core\Models\SysColor::class, 'name');
        }

        if (!array_key_exists('formateur_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgFormation::formateur.plural"), 'formateur_id', \Modules\PkgFormation\Models\Formateur::class, 'nom');
        }

    }

    /**
     * Crée une nouvelle instance de etatChapitre.
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
    public function getEtatChapitreStats(): array
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
            'table' => 'PkgAutoformation::etatChapitre._table',
            default => 'PkgAutoformation::etatChapitre._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('etatChapitre_view_type', $default_view_type);
        $etatChapitre_viewType = $this->viewState->get('etatChapitre_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('etatChapitre_view_type') === 'widgets') {
            $this->viewState->set("scope.etatChapitre.visible", 1);
        }else{
            $this->viewState->remove("scope.etatChapitre.visible");
        }
        
        // Récupération des données
        $etatChapitres_data = $this->paginate($params);
        $etatChapitres_stats = $this->getetatChapitreStats();
        $etatChapitres_filters = $this->getFieldsFilterable();
        $etatChapitre_instance = $this->createInstance();
        $etatChapitre_viewTypes = $this->getViewTypes();
        $etatChapitre_partialViewName = $this->getPartialViewName($etatChapitre_viewType);
        $etatChapitre_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.etatChapitre.stats', $etatChapitres_stats);
    
        $etatChapitres_permissions = [

            'edit-etatChapitre' => Auth::user()->can('edit-etatChapitre'),
            'destroy-etatChapitre' => Auth::user()->can('destroy-etatChapitre'),
            'show-etatChapitre' => Auth::user()->can('show-etatChapitre'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $etatChapitres_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($etatChapitres_data as $item) {
                $etatChapitres_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'etatChapitre_viewTypes',
            'etatChapitre_viewType',
            'etatChapitres_data',
            'etatChapitres_stats',
            'etatChapitres_filters',
            'etatChapitre_instance',
            'etatChapitre_title',
            'contextKey',
            'etatChapitres_permissions',
            'etatChapitres_permissionsByItem'
        );
    
        return [
            'etatChapitres_data' => $etatChapitres_data,
            'etatChapitres_stats' => $etatChapitres_stats,
            'etatChapitres_filters' => $etatChapitres_filters,
            'etatChapitre_instance' => $etatChapitre_instance,
            'etatChapitre_viewType' => $etatChapitre_viewType,
            'etatChapitre_viewTypes' => $etatChapitre_viewTypes,
            'etatChapitre_partialViewName' => $etatChapitre_partialViewName,
            'contextKey' => $contextKey,
            'etatChapitre_compact_value' => $compact_value,
            'etatChapitres_permissions' => $etatChapitres_permissions,
            'etatChapitres_permissionsByItem' => $etatChapitres_permissionsByItem
        ];
    }

}
