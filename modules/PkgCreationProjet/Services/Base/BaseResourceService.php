<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Services\Base;

use Modules\PkgCreationProjet\Models\Resource;
use Modules\Core\Services\BaseService;

/**
 * Classe ResourceService pour gérer la persistance de l'entité Resource.
 */
class BaseResourceService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour resources.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'lien',
        'description',
        'projet_id'
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
     * Constructeur de la classe ResourceService.
     */
    public function __construct()
    {
        parent::__construct(new Resource());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('resource');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('projet_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgCreationProjet::projet.plural"), 'projet_id', \Modules\PkgCreationProjet\Models\Projet::class, 'titre');
        }
    }

    /**
     * Crée une nouvelle instance de resource.
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
    public function getResourceStats(): array
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


}
