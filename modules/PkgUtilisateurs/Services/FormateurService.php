<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\Services;

use Modules\PkgUtilisateurs\Models\Formateur;
use Modules\Core\Services\BaseService;

/**
 * Classe FormateurService pour gérer la persistance de l'entité Formateur.
 */
class FormateurService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour formateurs.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'prenom',
        'prenom_arab',
        'nom_arab',
        'tele_num',
        'profile_image'
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
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'prenom_arab' => $data['prenom_arab'],
            'nom_arab' => $data['nom_arab'],
            'tele_num' => $data['tele_num'],
            'profile_image' => $data['profile_image'],
        ]);

        return $formateur;
    }
}
