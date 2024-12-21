<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services;

use Modules\PkgCompetences\Models\CategorieTechnology;
use Modules\Core\Services\BaseService;

/**
 * Classe CategorieTechnologyService pour gérer la persistance de l'entité CategorieTechnology.
 */
class CategorieTechnologyService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour categorieTechnologies.
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
     * Constructeur de la classe CategorieTechnologyService.
     */
    public function __construct()
    {
        parent::__construct(new CategorieTechnology());
    }

    /**
     * Crée une nouvelle instance de categorieTechnology.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        $categorieTechnology = parent::create([
            'nom' => $data['nom'],
            'description' => $data['description'],
        ]);

        return $categorieTechnology;
    }
}
