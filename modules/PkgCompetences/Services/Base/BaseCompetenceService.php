<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Modules\PkgCompetences\Models\Competence;
use Modules\Core\Services\BaseService;

/**
 * Classe CompetenceService pour gérer la persistance de l'entité Competence.
 */
class BaseCompetenceService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour competences.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'code',
        'nom',
        'description',
        'module_id'
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
     * Constructeur de la classe CompetenceService.
     */
    public function __construct()
    {
        parent::__construct(new Competence());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];

    }

    /**
     * Crée une nouvelle instance de competence.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        $competence = parent::create([
            'code' => $data['code'],
            'nom' => $data['nom'],
            'description' => $data['description'],
            'module_id' => $data['module_id'],
        ]);

        return $competence;
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getCompetenceStats(): array
    {

        $stats = [];

        // Ajouter les statistiques du propriétaire
        $contexteState = $this->getContextState();
        if ($contexteState !== null) {
            $stats[] = $contexteState;
        }

            $relationStatFiliere = parent::getStatsByRelation(
                \Modules\PkgCompetences\Models\Filiere::class,
                'modules.competences',
                'code'
            );
            $stats = array_merge($stats, $relationStatFiliere);

        return $stats;
    }

    public function getContextState()
    {
        if(!$this->contextState->isContextStateEnable()) return null; 
        $value = $this->contextState->getTitle();
        return [
                "icon" => "fas fa-filter",
                "label" => "Filtre",
                "value" =>  $value
        ];
    }
}
