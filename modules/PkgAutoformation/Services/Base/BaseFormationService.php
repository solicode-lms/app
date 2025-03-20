<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\Services\Base;

use Modules\PkgAutoformation\Models\Formation;
use Modules\Core\Services\BaseService;

/**
 * Classe FormationService pour gérer la persistance de l'entité Formation.
 */
class BaseFormationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour formations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'lien',
        'description',
        'is_officiel',
        'formateur_id',
        'formation_officiel_id',
        'competence_id'
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
     * Constructeur de la classe FormationService.
     */
    public function __construct()
    {
        parent::__construct(new Formation());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('formation');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('formateur_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgFormation::formateur.plural"), 'formateur_id', \Modules\PkgFormation\Models\Formateur::class, 'nom');
        }
        if (!array_key_exists('formation_officiel_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutoformation::formation.plural"), 'formation_officiel_id', \Modules\PkgAutoformation\Models\Formation::class, 'nom');
        }
        if (!array_key_exists('competence_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgCompetences::competence.plural"), 'competence_id', \Modules\PkgCompetences\Models\Competence::class, 'code');
        }
    }

    /**
     * Crée une nouvelle instance de formation.
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
    public function getFormationStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }



}
