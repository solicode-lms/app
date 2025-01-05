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
            ['field' => 'code', 'type' => 'String'],
            ['field' => 'nom', 'type' => 'String'],
            $this->generateManyToOneFilter('filiere_id', \Modules\PkgCompetences\Models\Filiere::class, 'code'),
            ['field' => 'Formateur_ManyToMany', 'type' => 'ManyToMany'],
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
        $groupe = parent::create([
            'code' => $data['code'],
            'nom' => $data['nom'],
            'description' => $data['description'],
            'filiere_id' => $data['filiere_id'],
        ]);

        return $groupe;
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
