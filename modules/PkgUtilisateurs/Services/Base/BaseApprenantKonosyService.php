<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\Services\Base;

use Modules\PkgUtilisateurs\Models\ApprenantKonosy;
use Modules\Core\Services\BaseService;

/**
 * Classe ApprenantKonosyService pour gérer la persistance de l'entité ApprenantKonosy.
 */
class BaseApprenantKonosyService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour apprenantKonosies.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'MatriculeEtudiant',
        'Nom',
        'Prenom',
        'Sexe',
        'EtudiantActif',
        'Diplome',
        'Principale',
        'LibelleLong',
        'CodeDiplome',
        'DateNaissance',
        'DateInscription',
        'LieuNaissance',
        'CIN',
        'NTelephone',
        'Adresse',
        'Nationalite',
        'Nom_Arabe',
        'Prenom_Arabe',
        'NiveauScolaire'
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
     * Constructeur de la classe ApprenantKonosyService.
     */
    public function __construct()
    {
        parent::__construct(new ApprenantKonosy());
    }

    /**
     * Crée une nouvelle instance de apprenantKonosy.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        return parent::create($data);
    }
}