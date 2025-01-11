<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\Services\Base;

use Modules\PkgUtilisateurs\Models\Formateur;
use Modules\Core\Services\BaseService;

/**
 * Classe FormateurService pour gérer la persistance de l'entité Formateur.
 */
class BaseFormateurService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour formateurs.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'adresse',
        'diplome',
        'echelle',
        'echelon',
        'matricule',
        'nom',
        'nom_arab',
        'prenom',
        'prenom_arab',
        'profile_image',
        'tele_num',
        'user_id'
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
     * Constructeur de la classe FormateurService.
     */
    public function __construct()
    {
        parent::__construct(new Formateur());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];

    }

    /**
     * Crée une nouvelle instance de formateur.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        $formateur = parent::create([
            'adresse' => $data['adresse'],
            'diplome' => $data['diplome'],
            'echelle' => $data['echelle'],
            'echelon' => $data['echelon'],
            'matricule' => $data['matricule'],
            'nom' => $data['nom'],
            'nom_arab' => $data['nom_arab'],
            'prenom' => $data['prenom'],
            'prenom_arab' => $data['prenom_arab'],
            'profile_image' => $data['profile_image'],
            'tele_num' => $data['tele_num'],
            'user_id' => $data['user_id'],
        ]);

        return $formateur;
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getFormateurStats(): array
    {

        $stats = [];

        

        return $stats;
    }

}
