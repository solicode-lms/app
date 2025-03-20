<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\Services\Base;

use Modules\PkgAutoformation\Models\WorkflowFormation;
use Modules\Core\Services\BaseService;

/**
 * Classe WorkflowFormationService pour gérer la persistance de l'entité WorkflowFormation.
 */
class BaseWorkflowFormationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour workflowFormations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'code',
        'titre',
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
     * Constructeur de la classe WorkflowFormationService.
     */
    public function __construct()
    {
        parent::__construct(new WorkflowFormation());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('workflowFormation');
        $this->fieldsFilterable = [];
    
    }

    /**
     * Crée une nouvelle instance de workflowFormation.
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
    public function getWorkflowFormationStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }



}
