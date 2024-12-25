<?php
// add CreateOrUpdate méthode 



namespace Modules\PkgUtilisateurs\Services;

use Modules\PkgUtilisateurs\Models\ApprenantKonosy;
use Modules\Core\Services\BaseService;

/**
 * Classe ApprenantKonosyService pour gérer la persistance de l'entité ApprenantKonosy.
 */
class ApprenantKonosyService extends BaseService
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
        $apprenantKonosy =  parent::create($data);
        $this->updateOrCreateDataFromApprenantKonosys($apprenantKonosy);
        return $apprenantKonosy;

    }

    /**
     * Met à jour ou crée un nouvel enregistrement basé sur des critères spécifiques.
     *
     * @param array $attributes Critères pour rechercher l'enregistrement.
     * @param array $values Données à mettre à jour ou à créer.
     * @return mixed
     */
    public function updateOrCreate(array $attributes, array $values)
    {
        $apprenantKonosy =  parent::updateOrCreate($attributes, $values);
        $this->updateOrCreateDataFromApprenantKonosys($apprenantKonosy);
        return $apprenantKonosy;
    }

    public function updateOrCreateDataFromApprenantKonosys($apprenantKonosy){

        // Create or Update Apprenant 
        (new ApprenantService())->updateOrCreate(
            ['matricule' => $apprenantKonosy->MatriculeEtudiant],
            [
            'nom' => $apprenantKonosy->Nom,
            'prenom' => $apprenantKonosy->Prenom,
            'prenom_arab' => $apprenantKonosy->Nom_Arabe,
            'nom_arab' => $apprenantKonosy->Prenom_Arabe,
            'tele_num' => $apprenantKonosy->NTelephone,
            'matricule' => $apprenantKonosy->MatriculeEtudiant,
            'sexe' => $apprenantKonosy->Sexe,
            'actif' => $apprenantKonosy->EtudiantActif,
            'diplome' => $apprenantKonosy->Diplome,
            'date_naissance' => $apprenantKonosy->DateNaissance,
            'date_inscription' => $apprenantKonosy->DateInscription,
            'lieu_naissance' => $apprenantKonosy->LieuNaissance,
            'cin' => $apprenantKonosy->CIN,
            'adresse' => $apprenantKonosy->Adresse,]
        );
    }
}
