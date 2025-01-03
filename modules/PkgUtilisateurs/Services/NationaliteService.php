<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\Services;

use Modules\PkgUtilisateurs\Models\Nationalite;
use Modules\Core\Services\BaseService;

/**
 * Classe NationaliteService pour gérer la persistance de l'entité Nationalite.
 */
class NationaliteService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour nationalites.
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
     * Constructeur de la classe NationaliteService.
     */
    public function __construct()
    {
        parent::__construct(new Nationalite());
    }

    /**
     * Crée une nouvelle instance de nationalite.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        $nationalite = parent::create([
            'code' => $data['code'],
            'nom' => $data['nom'],
            'description' => $data['description'],
        ]);

        return $nationalite;
    }
}
