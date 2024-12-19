<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\Services;

use Modules\PkgUtilisateurs\Models\Specialite;
use Modules\Core\Services\BaseService;

/**
 * Classe SpecialiteService pour gérer la persistance de l'entité Specialite.
 */
class SpecialiteService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour specialites.
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
     * Constructeur de la classe SpecialiteService.
     */
    public function __construct()
    {
        parent::__construct(new Specialite());
    }

    /**
     * Crée une nouvelle instance de specialite.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        $specialite = parent::create([
            'nom' => $data['nom'],
            'description' => $data['description'],
        ]);

        return $specialite;
    }
}
