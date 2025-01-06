<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\Services\Base;

use Modules\PkgUtilisateurs\Models\NiveauxScolaire;
use Modules\Core\Services\BaseService;

/**
 * Classe NiveauxScolaireService pour gérer la persistance de l'entité NiveauxScolaire.
 */
class BaseNiveauxScolaireService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour niveauxScolaires.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'code',
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
     * Constructeur de la classe NiveauxScolaireService.
     */
    public function __construct()
    {
        parent::__construct(new NiveauxScolaire());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];

    }

    /**
     * Crée une nouvelle instance de niveauxScolaire.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        $niveauxScolaire = parent::create([
            'code' => $data['code'],
            'nom' => $data['nom'],
            'description' => $data['description'],
        ]);

        return $niveauxScolaire;
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getNiveauxScolaireStats(): array
    {

        $stats = [];

        return $stats;
    }
}
