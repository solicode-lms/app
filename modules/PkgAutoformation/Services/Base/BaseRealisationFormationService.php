<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\Services\Base;

use Modules\PkgAutoformation\Models\RealisationFormation;
use Modules\Core\Services\BaseService;

/**
 * Classe RealisationFormationService pour gérer la persistance de l'entité RealisationFormation.
 */
class BaseRealisationFormationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour realisationFormations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'date_debut',
        'date_fin',
        'formation_id',
        'apprenant_id',
        'etat_formation_id'
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
     * Constructeur de la classe RealisationFormationService.
     */
    public function __construct()
    {
        parent::__construct(new RealisationFormation());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationFormation');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('formation_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutoformation::formation.plural"), 'formation_id', \Modules\PkgAutoformation\Models\Formation::class, 'nom');
        }
        if (!array_key_exists('apprenant_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgApprenants::apprenant.plural"), 'apprenant_id', \Modules\PkgApprenants\Models\Apprenant::class, 'nom');
        }
        if (!array_key_exists('etat_formation_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutoformation::etatFormation.plural"), 'etat_formation_id', \Modules\PkgAutoformation\Models\EtatFormation::class, 'code');
        }
    }

    /**
     * Crée une nouvelle instance de realisationFormation.
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
    public function getRealisationFormationStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }



}
