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

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];

    }

    /**
     * Crée une nouvelle instance de resource.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        $resource = parent::create([
            'nom' => $data['nom'],
            'lien' => $data['lien'],
            'description' => $data['description'],
            'projet_id' => $data['projet_id'],
        ]);

        return $resource;
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getResourceStats(): array
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
