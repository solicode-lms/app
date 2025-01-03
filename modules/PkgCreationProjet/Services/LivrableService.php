<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Services;

use Modules\PkgCreationProjet\Models\Livrable;
use Modules\Core\Services\BaseService;

/**
 * Classe LivrableService pour gérer la persistance de l'entité Livrable.
 */
class LivrableService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour livrables.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'titre',
        'nature_livrable_id',
        'projet_id',
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
     * Constructeur de la classe LivrableService.
     */
    public function __construct()
    {
        parent::__construct(new Livrable());
    }

    /**
     * Crée une nouvelle instance de livrable.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        return parent::create($data);
    }
}
