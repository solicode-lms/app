<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\Services\Base;

use Modules\PkgUtilisateurs\Models\Groupe;
use Modules\Core\Services\BaseService;

/**
 * Classe GroupeService pour gérer la persistance de l'entité Groupe.
 */
class BaseGroupeService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour groupes.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'code',
        'nom',
        'description',
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
     * Constructeur de la classe GroupeService.
     */
    public function __construct()
    {
        parent::__construct(new Groupe());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
            $this->generateManyToOneFilter(__("PkgCompetences::filiere.plural"), 'filiere_id', \Modules\PkgCompetences\Models\Filiere::class, 'code'),
        ];

    }

    /**
     * Crée une nouvelle instance de groupe.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        return parent::create($data);
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getGroupeStats(): array
    {

        $stats = [];

        

        return $stats;
    }

}
