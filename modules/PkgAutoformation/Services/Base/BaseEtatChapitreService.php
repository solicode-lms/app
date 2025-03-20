<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\Services\Base;

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
        'code',
        'nom',
        'workflow_chapitre_id',
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
     * Constructeur de la classe EtatChapitreService.
     */
    public function __construct()
    {
        parent::__construct(new EtatChapitre());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('etatChapitre');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('workflow_chapitre_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutoformation::workflowChapitre.plural"), 'workflow_chapitre_id', \Modules\PkgAutoformation\Models\WorkflowChapitre::class, 'code');
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

        

        return $stats;
    }



}
