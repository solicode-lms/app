<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\Services;

use Modules\PkgUtilisateurs\Models\Ville;
use Modules\Core\Services\BaseService;

/**
 * Classe VilleService pour gérer la persistance de l'entité Ville.
 */
class VilleService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour villes.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom'
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
     * Constructeur de la classe VilleService.
     */
    public function __construct()
    {
        parent::__construct(new Ville());
    }

    /**
     * Crée une nouvelle instance de ville.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        $ville = parent::create([
            'nom' => $data['nom'],
        ]);

        return $ville;
    }
}
