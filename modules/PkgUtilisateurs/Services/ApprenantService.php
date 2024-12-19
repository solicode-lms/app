<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\Services;

use Modules\PkgUtilisateurs\Models\Apprenant;
use Modules\Core\Services\BaseService;

/**
 * Classe ApprenantService pour gérer la persistance de l'entité Apprenant.
 */
class ApprenantService extends BaseService
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
        'date_inscription',
        'ville_id',
        'groupe_id',
        'niveaux_scolaires_id'
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
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'prenom_arab' => $data['prenom_arab'],
            'nom_arab' => $data['nom_arab'],
            'tele_num' => $data['tele_num'],
            'profile_image' => $data['profile_image'],
            'date_inscription' => $data['date_inscription'],
            'ville_id' => $data['ville_id'],
            'groupe_id' => $data['groupe_id'],
            'niveaux_scolaires_id' => $data['niveaux_scolaires_id'],
        ]);

        return $apprenant;
    }
}
