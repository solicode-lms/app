<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Services;

use Modules\PkgCreationProjet\Models\Resource;
use Modules\Core\Services\BaseService;

/**
 * Classe ResourceService pour gérer la persistance de l'entité Resource.
 */
class ResourceService extends BaseService
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
}
