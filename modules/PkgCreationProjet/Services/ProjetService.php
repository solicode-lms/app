<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Services;

use Modules\PkgCreationProjet\Models\Projet;
use Modules\Core\Services\BaseService;

/**
 * Classe ProjetService pour gérer la persistance de l'entité Projet.
 */
class ProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour projets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'titre',
        'travail_a_faire',
        'critere_de_travail',
        'description',
        'date_debut',
        'date_fin',
        'formateur_id'
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
     * Constructeur de la classe ProjetService.
     */
    public function __construct()
    {
        parent::__construct(new Projet());
    }

    /**
     * Crée une nouvelle instance de projet.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        return parent::create($data);
    }
}
