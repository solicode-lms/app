<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\Services\Base;

use Modules\PkgUtilisateurs\Models\Apprenant;
use Modules\Core\Services\BaseService;

/**
 * Classe ApprenantService pour gérer la persistance de l'entité Apprenant.
 */
class BaseApprenantService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour apprenants.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'prenom',
        'prenom_arab',
        'nom_arab',
        'tele_num',
        'profile_image',
        'matricule',
        'sexe',
        'actif',
        'diplome',
        'date_naissance',
        'date_inscription',
        'lieu_naissance',
        'cin',
        'adresse',
        'groupe_id',
        'niveaux_scolaire_id',
        'nationalite_id'
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
     * Constructeur de la classe ApprenantService.
     */
    public function __construct()
    {
        parent::__construct(new Apprenant());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
            $this->generateManyToOneFilter(__("PkgUtilisateurs::groupe.plural"), 'groupe_id', \Modules\PkgUtilisateurs\Models\Groupe::class, 'code'),
            $this->generateManyToOneFilter(__("PkgUtilisateurs::niveauxScolaire.plural"), 'niveaux_scolaire_id', \Modules\PkgUtilisateurs\Models\NiveauxScolaire::class, 'code'),
            $this->generateManyToOneFilter(__("PkgUtilisateurs::nationalite.plural"), 'nationalite_id', \Modules\PkgUtilisateurs\Models\Nationalite::class, 'code'),
        ];

    }

    /**
     * Crée une nouvelle instance de apprenant.
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
    public function getApprenantStats(): array
    {

        $stats = [];

        

        return $stats;
    }

}
