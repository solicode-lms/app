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
        'description',
        'lien',
        'nom',
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
            'description' => $data['description'],
            'lien' => $data['lien'],
            'nom' => $data['nom'],
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

        

        return $stats;
    }

}
