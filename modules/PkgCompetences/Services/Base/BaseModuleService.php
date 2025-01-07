<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Modules\PkgCompetences\Models\Module;
use Modules\Core\Services\BaseService;

/**
 * Classe ModuleService pour gérer la persistance de l'entité Module.
 */
class BaseModuleService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour modules.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'description',
        'masse_horaire',
        'filiere_id'
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
     * Constructeur de la classe ModuleService.
     */
    public function __construct()
    {
        parent::__construct(new Module());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];

    }

    /**
     * Crée une nouvelle instance de module.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        $module = parent::create([
            'nom' => $data['nom'],
            'description' => $data['description'],
            'masse_horaire' => $data['masse_horaire'],
            'filiere_id' => $data['filiere_id'],
        ]);

        return $module;
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getModuleStats(): array
    {

        $stats = [];

        // Ajouter les statistiques du propriétaire
        $contexteState = $this->getContextState();
        if ($contexteState !== null) {
            $stats[] = $contexteState;
        }


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
