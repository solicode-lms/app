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

    public function getViewTypes(){
        $viewTypes = [
            [
                'type' => 'table',
                'label' => 'Vue Tableau',
                'icon'  => 'fas fa-table',
            ],
            [
                'type' => 'widgets',
                'label' => 'Vue Widgets',
                'icon'  => 'fas fa-th-large',
            ],
        ];
        return $viewTypes;
    }

}
