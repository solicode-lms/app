<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Services\Base;

use Modules\PkgCreationProjet\Models\NatureLivrable;
use Modules\Core\Services\BaseService;

/**
 * Classe NatureLivrableService pour gérer la persistance de l'entité NatureLivrable.
 */
class BaseNatureLivrableService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour natureLivrables.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
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
     * Constructeur de la classe NatureLivrableService.
     */
    public function __construct()
    {
        parent::__construct(new NatureLivrable());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
            ['field' => 'nom', 'type' => 'String'],
        ];

    }

    /**
     * Crée une nouvelle instance de natureLivrable.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        $natureLivrable = parent::create([
            'nom' => $data['nom'],
            'description' => $data['description'],
        ]);

        return $natureLivrable;
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getNatureLivrableStats(): array
    {

        $stats = [];

        return $stats;
    }
}
