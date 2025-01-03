<?php
// Update or create PkgUtilisateur data from ApprenantKonosys object on create méthode



namespace Modules\PkgUtilisateurs\Services;

use Modules\PkgUtilisateurs\Models\ApprenantKonosy;
use Modules\Core\Services\BaseService;
use Carbon\Carbon;
use Modules\PkgCompetences\Services\FiliereService;

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



        // Create Filière if not exist 
        $codeDiplome = $apprenantKonosy->CodeDiplome;
        // Diviser la chaîne
        $code_filiere = substr($codeDiplome, 0, 3); // Prend les trois premiers caractères
        $code_groupe = $codeDiplome; // Garde la chaîne complète
        $nationalite_code = $apprenantKonosy->Nationalite;
        $niveau_scolaire_code = $apprenantKonosy->NiveauScolaire;

        // Create if not exist
        $filiere = (new FiliereService())->updateOrCreate(["code" => $code_filiere ],[ "code" => $code_filiere]);

        // Create if not exist
        $groupe = (new GroupeService())->updateOrCreate(["code" => $code_groupe ],
        [ "code" => $code_groupe ,
           "filiere_id" =>  $filiere->id
        ]);


        $nationalite = (new NationaliteService())->updateOrCreate(["code" => $nationalite_code ],[ "code" => $nationalite_code]);

        $niveau_scolaire = (new NiveauxScolaireService())->updateOrCreate(["code" => $niveau_scolaire_code ],[ "code" => $niveau_scolaire_code]);

        // Create or Update Apprenant 
        $apprenant = (new ApprenantService())->updateOrCreate(
            ['matricule' => $apprenantKonosy->MatriculeEtudiant],
            [
            'nom' => $apprenantKonosy->Nom,
            'prenom' => $apprenantKonosy->Prenom,
            'prenom_arab' => $apprenantKonosy->Nom_Arabe,
            'nom_arab' => $apprenantKonosy->Prenom_Arabe,
            'tele_num' => $apprenantKonosy->NTelephone,
            'matricule' => $apprenantKonosy->MatriculeEtudiant,
            'sexe' => $apprenantKonosy->Sexe,
            'actif' =>strtolower($apprenantKonosy->EtudiantActif) === 'oui'  ,
            'diplome' => $apprenantKonosy->Diplome,
            'date_naissance' => Carbon::parse(str_replace('/', '-',$apprenantKonosy->DateNaissance ))->format('Y/m/d') ,
            'date_inscription' => Carbon::parse(str_replace('/', '-',$apprenantKonosy->DateInscription ))->format('Y/m/d') ,
            'lieu_naissance' => $apprenantKonosy->LieuNaissance,
            'cin' => $apprenantKonosy->CIN,
            'adresse' => $apprenantKonosy->Adresse,
            'groupe_id' => $groupe->id,
            'nationalite_id' => $nationalite->id,
            'niveaux_scolaire_id' => $niveau_scolaire->id
            ]
        );

    }
}
