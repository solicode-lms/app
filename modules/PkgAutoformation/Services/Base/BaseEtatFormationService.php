<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\Services\Base;

use Modules\PkgAutoformation\Models\EtatFormation;
use Modules\Core\Services\BaseService;

/**
 * Classe EtatFormationService pour gérer la persistance de l'entité EtatFormation.
 */
class BaseEtatFormationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour etatFormations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'code',
        'nom',
        'description',
        'workflow_formation_id'
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
     * Constructeur de la classe EtatFormationService.
     */
    public function __construct()
    {
        parent::__construct(new EtatFormation());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('etatFormation');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('workflow_formation_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutoformation::workflowFormation.plural"), 'workflow_formation_id', \Modules\PkgAutoformation\Models\WorkflowFormation::class, 'code');
        }
    }

    /**
     * Crée une nouvelle instance de etatFormation.
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
    public function getEtatFormationStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }



}
