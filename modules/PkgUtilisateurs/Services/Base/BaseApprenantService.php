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
        'actif',
        'adresse',
        'cin',
        'date_inscription',
        'date_naissance',
        'diplome',
        'groupe_id',
        'lieu_naissance',
        'matricule',
        'nationalite_id',
        'niveaux_scolaire_id',
        'nom',
        'nom_arab',
        'prenom',
        'prenom_arab',
        'profile_image',
        'sexe',
        'tele_num'
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
        $apprenant = parent::create([
            'actif' => $data['actif'],
            'adresse' => $data['adresse'],
            'cin' => $data['cin'],
            'date_inscription' => $data['date_inscription'],
            'date_naissance' => $data['date_naissance'],
            'diplome' => $data['diplome'],
            'groupe_id' => $data['groupe_id'],
            'lieu_naissance' => $data['lieu_naissance'],
            'matricule' => $data['matricule'],
            'nationalite_id' => $data['nationalite_id'],
            'niveaux_scolaire_id' => $data['niveaux_scolaire_id'],
            'nom' => $data['nom'],
            'nom_arab' => $data['nom_arab'],
            'prenom' => $data['prenom'],
            'prenom_arab' => $data['prenom_arab'],
            'profile_image' => $data['profile_image'],
            'sexe' => $data['sexe'],
            'tele_num' => $data['tele_num'],
        ]);

        return $apprenant;
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getApprenantStats(): array
    {

        $stats = [];

        
            $relationStatGroupe = parent::getStatsByRelation(
                \Modules\PkgUtilisateurs\Models\Groupe::class,
                'apprenants',
                'code'
            );
            $stats = array_merge($stats, $relationStatGroupe);

        return $stats;
    }

}
