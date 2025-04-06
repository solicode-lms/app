<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\Services\Base;

use Modules\PkgWidgets\Models\WidgetUtilisateur;
use Modules\Core\Services\BaseService;

/**
 * Classe WidgetUtilisateurService pour gérer la persistance de l'entité WidgetUtilisateur.
 */
class BaseWidgetUtilisateurService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour widgetUtilisateurs.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'user_id',
        'widget_id',
        'titre',
        'sous_titre',
        'visible'
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
     * Constructeur de la classe WidgetUtilisateurService.
     */
    public function __construct()
    {
        parent::__construct(new WidgetUtilisateur());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('widgetUtilisateur');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('user_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutorisation::user.plural"), 'user_id', \Modules\PkgAutorisation\Models\User::class, 'name');
        }
        if (!array_key_exists('widget_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgWidgets::widget.plural"), 'widget_id', \Modules\PkgWidgets\Models\Widget::class, 'name');
        }
        if (!array_key_exists('visible', $scopeVariables)) {
        $this->fieldsFilterable[] = ['field' => 'visible', 'type' => 'Boolean', 'label' => 'visible'];
        }
    }

    /**
     * Crée une nouvelle instance de widgetUtilisateur.
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
    public function getWidgetUtilisateurStats(): array
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
                'icon'  => 'fas fa-table',
            ],
            [
                'type'  => 'widgets',
                'label' => 'Vue Widgets',
                'icon'  => 'fas fa-th-large',
            ],
        ];
    }

    /**
     * Retourne le nom de la vue partielle selon le type de vue sélectionné
     */
    public function getPartialViewName(string $viewType): string
    {
        return match ($viewType) {
            'table' => 'PkgWidgets::widgetUtilisateur._table',
            'widgets' => 'PkgWidgets::widgetUtilisateur._widgets',
            default => 'PkgWidgets::widgetUtilisateur._table',
        };
    }

    public function prepareDataForIndexView(array $params = [], ?string $viewType = null): array
    {
        $data = $this->paginate($params);
        $stats = $this->getwidgetUtilisateurStats();
        $this->viewState->set('stats.widgetUtilisateur.stats'  , $stats);

        return [
            'widgetUtilisateurs_data' =>$data,
            'widgetUtilisateurs_stats' => $stats,
            'widgetUtilisateurs_filters' => $this->getFieldsFilterable(),
            'widgetUtilisateur_instance' => $this->createInstance(),
            'viewType' => $viewType ?? 'table',
            'partialViewName' => $this->getPartialViewName($viewType ?? 'table'),
            'viewTypes' => $this->getViewTypes(),
        ];
    }

}
